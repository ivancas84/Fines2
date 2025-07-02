<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

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
    
    $i = 0;

    $existenDatos = false;
    foreach($alumnosData as $ad){
        try {
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            $i++;
            echo "<strong>Alumno: " . $i . ";</strong><br>";
            $cuilDni = Persona_::cuilDni($ad["dni_cuil"]);
            if(empty($cuilDni["dni"])){
                echo $ad["apellidos"] . " " . $ad["nombres"] . "<br>";
                throw new Exception("DNI vacío, no se procesará el alumno");
            }

            if(in_array($cuilDni["dni"], $dnisProcesados)){
                echo $ad["apellidos"] . " " . $ad["nombres"] . " " . $ad["dni"] . "<br>";
                throw new Exception("DNI ya procesado, no se procesará el alumno");
            }
            $dnisProcesados[] = $cuilDni["dni"];

            $ad["numero_documento"] = $cuilDni["dni"];
            $ad["cuil"] = $cuilDni["cuil"];

            echo $ad["apellidos"] . " " . $ad["nombres"] . " " . $ad["numero_documento"] . "<br>";


            /** @var Persona_ */ $persona = Persona_::createByUnique("Fines2\Persona_", $ad);
            if ($persona->_status === 0){
                if(!Persona_::nombreParecido($persona->toArray(), $ad))
                    throw new Exception("El nombre registrado de la persona es diferente " . $persona->getLabel());
                $modifyQueries->buildUpdateSql($persona);
                
            }
            else if ($persona->_status < 0)
                $modifyQueries->buildInsertSql($persona);

            /** @var Alumno_ */ $alumno = Alumno_::createByUnique("Fines2\Alumno_", ["persona"=>$persona->id]);
            $alumno->set("plan", $comision->planificacion_->plan);
            $modifyQueries->buildPersistSqlByStatus($alumno);

            /** @var AlumnoComision_ */ $alumnoComision = AlumnoComision_::createByUnique("Fines2\AlumnoComision_", ["alumno" => $alumno->id, "comision" => $comision->id]);

            if ($alumnoComision->_status < 0){
                $alumnoComision->set("estado", ($modifyQueries->getDetailAction("alumno", $alumno->id) == "insert") ? "Ingresante" : "Incorporado");
                $alumnoComision->set("observaciones", "Importado desde lista de alumnos");
                $modifyQueries->buildInsertSql($alumnoComision);
            }
                
            if(isset($alumno->anio_inscripcion) 
                && isset($alumno->anio_ingreso) 
                && ($alumno->anio_ingreso < $alumno->anio_inscripcion)){
                    echo " - Alumno tiene anio_ingreso menor a anio_inscripcion<br>";
            }

            echo "<pre>";
            
            echo "<strong>Persistencia</strong></br>";
            if(!empty($modifyQueries->detail)){
                print_r($modifyQueries->detail);
            } else {
                echo "Sin modificaciones<br>";
            }
            if(!empty($persona->_changeLog)){
                echo "<strong>persona</strong></br>";
                print_r($persona->_changeLog);
            }
            if(!empty($alumno->_changeLog)){
                echo "<strong>alumno</strong></br>";
                print_r($alumno->_changeLog);
            }
            if(!empty($alumnoComision->_changeLog)){
                echo "<strong>alumno_comision</strong></br>";
                print_r($alumnoComision->_changeLog);
            }
            echo "</pre>";

        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";
        if(!empty($modifyQueries->detail)){
            $existenDatos = true;
        }
    }

    if($existenDatos){
        include plugin_dir_path(__FILE__) . 'cac2_form_process.html';
    } else {
        echo "No existen datos para registrar";
    }

}