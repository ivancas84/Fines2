<?php

use App\Context;
use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\PersonaDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Cargar Alumnos Comisión',
    'Cargar Alumnos Comisión', 
    'edit_posts', 
    FINES_PLUGIN.'-cac3', 
    'cac3_page'
);

function cac3_page() {
    wp_page_message();

    $db = Context::getFinesDb();

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    
    /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["id" => $_GET['comision_id']]);
    
    if(empty($comision)) throw new Exception("No se ha encontrado la comision");
 
    echo "<h1>Cargar alumnos en comisión " . $comision->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'cac3_form_html.php';
        return;
    }

    $rawData = trim($_POST['data']);
    $alumnosData = ValueTypesUtils::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de alumnos a procesar ". count($alumnosData) . "</h2>";
    $dnisProcesados = [];
    
    $i = 0;

    foreach($alumnosData as $ad){
        try {

            /** @var ModifyQueries */ $modifyQueries = Context::getFinesDb()->CreateModifyQueries();
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

            /** @var Persona_ */ $persona = PersonaDAO::createPersonaByUnique($ad);
            $modifyQueries->persistSqlByStatus($persona);
            if ($persona->_status === 1) {
                echo "Persona existente<br>";
            } else if ($persona->_status === 0 ){
                echo "Persona actualizada<br>";
                $modifyQueries->updateSql($persona);
            } else {
                echo "Persona insertada<br>";
                $modifyQueries->insertSql($persona);
            }


            /** @var Alumno_ */ $alumno = $db->createEntityByUnique("alumno", ["persona" => $persona->id, "plan" => $comision->planificacion_->plan]);

            if($alumno->tiene_certificado && !ValueTypesUtils::toBool($ad["tiene_certificado"])){
                echo "ERROR: En el sistema tiene certificado pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_certificado && ValueTypesUtils::toBool($ad["tiene_certificado"])){
                echo "Se ha cargado el valor de tiene certificado.<br>";
                $alumno->tiene_certificado = true;
            }

            if($alumno->tiene_constancia && !ValueTypesUtils::toBool($ad["tiene_constancia"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_constancia && ValueTypesUtils::toBool($ad["tiene_constancia"])){
                echo "Se ha cargado el valor de tiene contancia.<br>";
                $alumno->tiene_constancia = true;
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["tiene_dni"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_dni && ValueTypesUtils::toBool($ad["tiene_dni"])){
                echo "Se ha cargado el valor de tiene dni.<br>";
                $alumno->tiene_dni = true;
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["tiene_partida"])){
                echo "ERROR: En el sistema tiene partida pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_partida && ValueTypesUtils::toBool($ad["tiene_partida"])){
                echo "Se ha cargado el valor de tiene partida.<br>";
                $alumno->tiene_partida = true;
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["previas_completas"])){
                echo "ERROR: En el sistema previas completas pero en la hoja de calculo no.<br>";
            }
            elseif(!$alumno->previas_completas && ValueTypesUtils::toBool($ad["previas_completas"])){
                echo "Se ha cargado el valor de previas completas.<br>";
                $alumno->previas_completas = true;
            }
            
            if ($alumno->_status === 1) {
                echo "Alumno existente<br>";
            } else if ($alumno->_status === 0 ){
                echo "Alumno actualizado<br>";
                $modifyQueries->updateSql($alumno);
            } else {
                echo "Alumno insertado<br>";
                $modifyQueries->insertSql($alumno);
            }

            


            $alumnoComisionData = ["alumno" => $alumno->id, "comision" => $comision->id, "observaciones"=> "Importado de lista de alumnos"];
            if($alumno->_status == -1){
                /** @var AlumnoComision_ */ $alumnoComision = $db->createEntity("alumno_comision", $alumnoComisionData);
                $alumnoComision->estado = "Ingresante";            
            } else {
                /** @var AlumnoComision_ */ $alumnoComision = $db->createEntityByUnique("alumno_comision", ["persona" => $persona->id, "plan" => $comision->planificacion_->plan]);
                if($alumnoComision->_status == -1)
                    $alumnoComision->estado = "Incorporado";
            }
            
            if ($alumnoComision->_status === 1) {
                echo "Alumno en comisión existente<br>";
            } else if ($alumnoComision->_status === 0 ){
                echo "Alumno en comisión actualizado<br>";
                $modifyQueries->updateSql($alumnoComision);
            } else {
                echo "Alumno en comisión insertado<br>";
                $modifyQueries->insertSql($alumnoComision);
            }


            $modifyQueries->process();

        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "Finalizado<br>";

    }


}