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
            

            $tieneActual = !empty($alumno->anio_ingreso);
            $tieneNuevo  = !empty($ad["anio_ingreso"]);

            $anioActual = $tieneActual ? (int)$alumno->anio_ingreso : null;
            $anioNuevo  = $tieneNuevo  ? (int)$ad["anio_ingreso"] : null;

            if ($tieneActual && $tieneNuevo && $anioActual > $anioNuevo) {
                echo "ERROR: En el sistema el año ingreso es mayor al de la hoja de calculo.<br>";
            }

            else if ($tieneNuevo) {
                echo "Se ha cargado el valor de año ingreso.<br>";
                $alumno->set("confirmado_direccion", true);
                $alumno->sset("anio_ingreso", $anioNuevo);
            }

            if($alumno->tiene_certificado && !ValueTypesUtils::toBool($ad["tiene_certificado"])){
                echo "ERROR: En el sistema tiene certificado pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_certificado && ValueTypesUtils::toBool($ad["tiene_certificado"])){
                echo "Se ha cargado el valor de tiene certificado.<br>";
                $alumno->set("tiene_certificado", true);

            }

            if($alumno->tiene_constancia && !ValueTypesUtils::toBool($ad["tiene_constancia"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_constancia && ValueTypesUtils::toBool($ad["tiene_constancia"])){
                echo "Se ha cargado el valor de tiene contancia.<br>";
                $alumno->set("tiene_constancia", true);
            }

            if($alumno->tiene_dni && !ValueTypesUtils::toBool($ad["tiene_dni"])){
                echo "ERROR: En el sistema tiene constancia pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_dni && ValueTypesUtils::toBool($ad["tiene_dni"])){
                echo "Se ha cargado el valor de tiene dni.<br>";
                $alumno->set("tiene_dni", true);

            }

            if($alumno->tiene_partida && !ValueTypesUtils::toBool($ad["tiene_partida"])){
                echo "ERROR: En el sistema tiene partida pero en la hoja de calculo no.<br>";
            }
            else if(!$alumno->tiene_partida && ValueTypesUtils::toBool($ad["tiene_partida"])){
                echo "Se ha cargado el valor de tiene partida.<br>";
                $alumno->set("tiene_partida", true);
            }

            if($alumno->previas_completas && !ValueTypesUtils::toBool($ad["previas_completas"])){
                echo "ERROR: En el sistema previas completas pero en la hoja de calculo no.<br>";
            }
            elseif(!$alumno->previas_completas && ValueTypesUtils::toBool($ad["previas_completas"])){
                echo "Se ha cargado el valor de previas completas.<br>";
                $alumno->set("previas_completas", true);
            }

            if(ValueTypesUtils::hayPalabrasNuevas($alumno->observaciones, $ad["observaciones"])){
                echo "Se actualizara el valor de observaciones. Antiguo = " . $alumno->observaciones . ". Nuevo = " . $ad["observaciones"] . "<br>";
                (empty($alumno->observaciones)) ? $alumno->set("observaciones", $ad["observaciones"]) : $alumno->set("observaciones", $alumno->observaciones . " - " . $ad["observaciones"]);
            }
            else {
                echo "No se actualizara el valor de observaciones.<br>";
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
                $alumnoComision->set("estado", "Ingresante");            
            } else {
                /** @var AlumnoComision_ */ $alumnoComision = $db->createEntityByUnique("alumno_comision", ["alumno" => $alumno->id, "comision" => $comision->id]);
                if($alumnoComision->_status == -1)
                    $alumnoComision->set("estado", "Incorporado");
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
        
        echo "Finalizado<br><br>";

    }


}