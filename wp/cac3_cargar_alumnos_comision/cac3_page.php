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
    
    if(empty($comision)) throw new Exception("No se ha encontrado la comisión");
 
    echo "<h1>Cargar alumnos en comisión " . $comision->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'cac3_form_html.php';
        return;
    }

    $rawData = trim($_POST['data']);
    $alumnosData = ValueTypesUtils::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de alumnos a procesar: " . count($alumnosData) . "</h2>";
    
    $dnisProcesados = [];
    $i = 0;

    foreach($alumnosData as $ad){
        try {
            /** @var ModifyQueries */ $modifyQueries = Context::getFinesDb()->CreateModifyQueries();
            $i++;
            echo "<strong>Alumno: " . $i . ";</strong><br>";

            $cuilDni = Persona_::cuilDni($ad["dni_cuil"] ?? '');
            if(empty($cuilDni["dni"])){
                echo ($ad["apellidos"] ?? '') . " " . ($ad["nombres"] ?? '') . "<br>";
                throw new Exception("DNI vacío, no se procesará el alumno");
            }

            if(in_array($cuilDni["dni"], $dnisProcesados)){
                echo ($ad["apellidos"] ?? '') . " " . ($ad["nombres"] ?? '') . " " . $cuilDni["dni"] . "<br>";
                throw new Exception("DNI ya procesado, no se procesará el alumno");
            }
            $dnisProcesados[] = $cuilDni["dni"];

            $ad["numero_documento"] = $cuilDni["dni"];
            $ad["cuil"] = $cuilDni["cuil"];

            echo ($ad["apellidos"] ?? '') . " " . ($ad["nombres"] ?? '') . " " . $ad["numero_documento"] . "<br>";

            /** @var Persona_ */ $persona = PersonaDAO::createPersonaByUnique($ad);
            if ($persona->_status === 1) {
                echo "Persona existente<br>";
            } else if ($persona->_status === 0 ){
                echo "Persona actualizada<br>";
                $modifyQueries->updateSql($persona);
            } else {
                echo "Persona insertada<br>";
                $modifyQueries->insertSql($persona);
            }

            /** @var Alumno_ */ $alumno = $db->createEntityByUnique("alumno", [
                "persona" => $persona->id, 
                "plan" => $comision->planificacion_->plan
            ]);

            // === Año de ingreso ===
            $tieneActual = !empty($alumno->anio_ingreso);
            $tieneNuevo  = !empty($ad["anio_ingreso"] ?? '');

            $anioActual = $tieneActual ? (int)substr($alumno->anio_ingreso, 0, 1) : null;
            $anioNuevo  = $tieneNuevo  ? (int)substr($ad["anio_ingreso"], 0, 1) : null;

            if ($tieneActual && $tieneNuevo && $anioActual > $anioNuevo) {
                echo "ERROR: En el sistema el año ingreso es mayor al de la hoja de cálculo.<br>";
            }
            else if ($tieneNuevo && $anioActual != $anioNuevo) {
                echo "Se ha cargado el valor de año ingreso.<br>";
                $alumno->set("confirmado_direccion", true);
                $alumno->sset("anio_ingreso", $ad["anio_ingreso"]);
            }

            // === Módulo / Semestre ingreso ===
            if (isset($ad["modulo"]) && !empty(substr($ad["modulo"], 0, 1))) {
                $modulo = (int)$ad["modulo"];
                if ($modulo % 2 !== 0) {
                    echo "Se ha asignado semestre ingreso = 1 (módulo impar).<br>";
                    $alumno->set("semestre_ingreso", 1);
                } else {
                    echo "Se ha asignado semestre ingreso = 2 (módulo par).<br>";
                    $alumno->set("semestre_ingreso", 2);
                }
            }

            // === Campos booleanos (ahora seguros) ===
            $camposBooleanos = [
                'tiene_certificado',
                'tiene_constancia',
                'tiene_dni',
                'tiene_partida',
                'previas_completas'
            ];

            foreach ($camposBooleanos as $campo) {
                $valorActual = $alumno->{$campo} ?? false;
                $valorNuevo  = isset($ad[$campo]) ? ValueTypesUtils::toBool($ad[$campo]) : null;

                if ($valorNuevo === null) {
                    continue; // El campo no viene en el Excel → lo ignoramos
                }

                if ($valorActual && !$valorNuevo) {
                    echo "ERROR: En el sistema tiene {$campo} pero en la hoja de cálculo no.<br>";
                }
                elseif (!$valorActual && $valorNuevo) {
                    echo "Se ha cargado el valor de {$campo}.<br>";
                    $alumno->set($campo, true);
                }
            }

            // === Observaciones ===
            if (ValueTypesUtils::hayPalabrasNuevas($alumno->observaciones ?? '', $ad["observaciones"] ?? '')) {
                $nuevaObs = $ad["observaciones"] ?? '';
                echo "Se actualizará el valor de observaciones. Antiguo = " . ($alumno->observaciones ?? '') . 
                     ". Nuevo = " . $nuevaObs . "<br>";
                
                $alumno->set("observaciones", empty($alumno->observaciones) 
                    ? $nuevaObs 
                    : $alumno->observaciones . " - " . $nuevaObs);
            } else {
                echo "No se actualizará el valor de observaciones.<br>";
            }
            
            // === Guardar Alumno ===
            if ($alumno->_status === 1) {
                echo "Alumno existente<br>";
            } else if ($alumno->_status === 0 ){
                echo "Alumno actualizado<br>";
                $modifyQueries->updateSql($alumno);
            } else {
                echo "Alumno insertado<br>";
                $modifyQueries->insertSql($alumno);
            }

            // === AlumnoComision ===
            $alumnoComisionData = [
                "alumno" => $alumno->id, 
                "comision" => $comision->id, 
                "observaciones" => "Importado de lista de alumnos"
            ];

            if ($alumno->_status == -1) {
                /** @var AlumnoComision_ */ $alumnoComision = $db->createEntity("alumno_comision", $alumnoComisionData);
                $alumnoComision->set("estado", "Ingresante");            
            } else {
                /** @var AlumnoComision_ */ $alumnoComision = $db->createEntityByUnique("alumno_comision", [
                    "alumno" => $alumno->id, 
                    "comision" => $comision->id
                ]);
                if ($alumnoComision->_status == -1) {
                    $alumnoComision->set("estado", "Incorporado");
                }
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