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
    'cac2_page'
);

function cac3_page() {
    wp_page_message();

    $db = Context::getFinesDb();

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    
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

            /** @var Persona_ */ $persona = PersonaDAO::createAndPersist($modifyQueries, $ad);

            /** @var Alumno_ */ $alumno = AlumnoDAO::createAndPersist($modifyQueries, $persona->id, $comision->planificacion_->plan); 
            
            /** @var AlumnoComision_ */ $alumnoComision = AlumnoComisionDAO::createAndPersist($modifyQueries, $alumno->id, $comision->id, "Importado desde lista de alumnos");
                
            if($alumno->tiene_certificado && !ValueTypesUtils::toBool($ad["tiene_certificado"])){
                echo "ERROR: En el sistema tiene certificado pero en la hoja de calculo no";
            }
            else if(!$alumno->tiene_certificado && ValueTypesUtils::toBool($ad["tiene_certificado"])){
                $modifyQueries->updateKeyValueSqlById("alumno", "tiene_certificado", true, $alumno->id);
            }

            if($alumno->tiene_constancia && !ValueTypesUtils::toBool($ad["tiene_constancia"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no";
            }
            else if(!$alumno->tiene_constancia && ValueTypesUtils::toBool($ad["tiene_constancia"])){
                $modifyQueries->updateKeyValueSqlById("alumno", "tiene_constancia", true, $alumno->id);
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["tiene_dni"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no";
            }
            else if(!$alumno->tiene_dni && ValueTypesUtils::toBool($ad["tiene_dni"])){
                $modifyQueries->updateKeyValueSqlById("alumno", "tiene_dni", true, $alumno->id);
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["tiene_partida"])){
                echo "ERROR: En el sistema tiene partida pero en la hoja de calculo no";
            }
            else if(!$alumno->tiene_partida && ValueTypesUtils::toBool($ad["tiene_partida"])){
                $modifyQueries->updateKeyValueSqlById("alumno", "tiene_partida", true, $alumno->id);
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["previas_completas"])){
                echo "ERROR: En el sistema previas completas pero en la hoja de calculo no";
            }
            elseif(!$alumno->previas_completas && ValueTypesUtils::toBool($ad["previas_completas"])){
                $modifyQueries->updateKeyValueSqlById("alumno", "previas_completas", true, $alumno->id);
            }

            $modifyQueries->process();

        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";

        if(!empty($modifyQueries->detail)) {
            $existenDatos = true;
        }
    }

    if($existenDatos){
        include plugin_dir_path(__FILE__) . 'cac3_form_process_html.php';
    } else {
        echo "No existen datos para registrar";
    }

}