<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use SqlOrganize\Sql\Entity;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\Comision_;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Cargar Alumnos Comisión',
    'Cargar Alumnos Comisión', 
    'edit_posts', 
    'fines-plugin-cac2', 
    'cac2_cargar_alumnos_comision_page'
);

function cac2_cargar_alumnos_comision_page() {
    wp_page_message();

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["id" => $_GET['comision_id']]);
    if(empty($comision)) throw new Exception("No se ha encontrado la comision");
 
    echo "<h1>Cargar alumnos en comisión " . $comision->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'cac2_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $alumnosData = ValueTypesUtils::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de alumnos a procesar ". count($alumnosData) . "</h2>";
    $dnisProcesados = [];
    $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id]);
    $alumnosComision = ValueTypesUtils::dictOfObjByPropertyNames($alumnosComision, "numero_documento");

    $i = 0;

    foreach($alumnosData as $data){
        try {
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            $i++;
            echo "<br><br>Alumno: " . $i . ";<br>";
            $cuilDni = Persona_::cuilDni($data["dni_cuil"]);
            if(empty($cuilDni["dni"])){
                echo $data["apellidos"] . " " . $data["nombres"] . "<br>";
                throw new Exception("DNI vacío, no se procesará el alumno");
            }

            if(in_array($cuilDni["dni"], $dnisProcesados)){
                echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["dni"] . "<br>";
                throw new Exception("DNI ya procesado, no se procesará el alumno");
            }
            $dnisProcesados[] = $cuilDni["dni"];

            $data["numero_documento"] = $cuilDni["dni"];
            $data["cuil"] = $cuilDni["cuil"];

            echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["numero_documento"] . "<br>";

            /** @var Persona_ */ $persona = Entity::createByUnique("\Fines2\Persona_", $data);
            if($persona->_status < 0) {//no existe persona, crearla
                $modifyQueries->buildInsertSql($persona);
                echo ' - Persona agregada, id '. $persona->id . '<a target="_blank" href="https://planfines2.com.ar/wp/wp-admin/admin.php?page=fines-plugin-administrar-persona-page&persona_id=' . $persona->id . '">Ver</a><br>';
            
            } else { //existe persona, verificar datos
                
                if(!Tools::nombreParecido($persona->toArray(), $data))
                    throw new Exception("El nombre registrado de la persona es diferente " . $persona["nombres"] . " " . $persona["apellidos"]);
               
                $personaAux = clone $persona;
                $personaAux->ssetFromArray($data);
                $compareResult = $personaAux->compare($persona);
                if(empty($compareResult)){
                    echo ' - Persona ya existe, no se actualiza id '. $persona->id . '<a target="_blank" href="https://planfines2.com.ar/wp/wp-admin/admin.php?page=fines-plugin-administrar-persona-page&persona_id=' . $persona->id . '">Ver</a><br>';
                } else {
                    $modifyQueries->buildUpdateSql($persona);
                    echo " - Persona actualizada id ". $persona->id . "<br>";
                    echo "<pre>";
                    print_r($compareResult);
                    echo "</pre>";        
                }
                   
            }

            $existeEnComision = false;
            /** @var Alumno_ */ $alumno = Entity::createByUnique("\Fines2\Alumno_", ["persona" => $persona->id]);
            $alumno->plan = $comision->planificacion_->plan;
            if($alumno->_status < 0) { //no existe el alumno, verificar si existe persona
                $modifyQueries->buildInsertSql($alumno);
            } else {
                $alumnoAux = clone $alumno; //clonar para no modificar el original
                $alumno->ssetFromArray($data);
                $compareResult = $alumno->compare($alumnoAux);
                if(empty($compareResult)){ //no hay cambios
                    echo " - Alumno ya existe, no se actualiza id ". $alumno->id . "<br>";
                } else { //hay cambios, actualizar
                    $modifyQueries->buildUpdateSql($alumno);
                    echo " - Alumno actualizado id ". $alumno->id . "<br>";
                    echo "<pre>";
                    print_r($compareResult);
                    echo "</pre>";        
                }

                /** @var Comision_[] */ $comisiones = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["alumno" => $alumno->id]);
                if(!empty($comisiones)) {
                    echo " - Alumno cargado en " . count($comisiones) . " comisiones: <br>";
                    foreach($comisiones as $com){
                       echo " -- " . $com->getLabel();;
                       if($com->id == $comision->id) {
                            $existeEnComision = true;
                            echo " (ya existe en la comision actual)";
                       }
                       echo "<br>";
                    }
                } else {
                    echo " - Alumno no tiene comisiones<br>";
                }
            }

            if(!$existeEnComision){
                $alumno_comision = new AlumnoComision_();
                $alumno_comision->alumno = $alumno->id;
                $alumno_comision->comision = $comision->id;
                $alumno_comision->estado = "Ingresante";
                $alumno_comision->observaciones = "Importado desde lista de alumnos";
                $modifyQueries->buildInsertSql($alumno_comision);
                echo " - Alumno ingresante a la comision id ac ". $alumno_comision->id . "<br>";
            }

            if(isset($alumno->anio_inscripcion) 
                && isset($alumno->anio_ingreso) 
                && ($alumno->anio_ingreso < $alumno->anio_inscripcion)){
                    echo " - Alumno tiene anio_ingreso menor a anio_inscripcion<br>";
            }


            $modifyQueries->process();
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";
    }
    echo "</pre>";
}