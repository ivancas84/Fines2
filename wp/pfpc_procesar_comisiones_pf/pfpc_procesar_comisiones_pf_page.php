<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/db-config.php');

use Fines2\Comision_;
use Fines2\Calificacion_;
use Fines2\Curso_;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
use Fines2\CalificacionDAO;
use Fines2\CursoDAO;
use Fines2\PersonaDAO;
use Fines2\Toma_;
use Fines2\TomaDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Procesar Comisiones PF',
    'Procesar Comisiones PF', 
    'edit_posts', 
    'fines-plugin-pfpc', 
    'pfpc_procesar_comisiones_pf_page'
  );

  
function pfpc_procesar_comisiones_pf_page() {
    wp_page_message();

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'pfpc_form_html.php';
        return;
    }

    $rawData = trim($_POST['data']);
    $dataText = file_get_contents($file);

    $dict = []; // Equivalent to Dictionary<string, object>
    $procesar_docente = false;
    $dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes"]; // Assuming these are the days
    $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario"=>CALENDARIO_ID_ACTUAL]);

    $pfids = ValueTypesUtils::arrayOfName($comisiones, "pfid");
    foreach (array_filter(explode(PHP_EOL, $dataText)) as $line) {
        
        $modifyQueries = $db->CreateModifyQueries();
        if ($procesar_docente) {
            // Procesar docente
            if (strpos($line, "*") !== false) {
                echo "Docente sin designar en curso " . ($dict["comision__pfid"] ?? '') . " " . ($dict["asignatura__codigo"] ?? '') . "<br/>";
                $procesar_docente = false;
                continue;
            } elseif (strpos($line, "-") === false) {
                echo "Salto de línea, en curso " . ($dict["comision__pfid"] ?? '') . " " . ($dict["asignatura__codigo"] ?? '') . "<br/>";
                continue;
            } else {
                echo "Procesando docente de curso " . ($dict["comision__pfid"] ?? '') . " " . ($dict["asignatura__codigo"] ?? '') . "<br/>";
                $procesar_docente = false;

            $line = str_replace("--", "-", $line); //se han encontrado cuils mal escritos con doble guion

            // Extract CUIL
            preg_match('/\d{2}-\d{8}-\d/', $line, $matches);
            if (!empty($matches)) {
                $cuil = $matches[0];
                $cuilParts = explode("-", $cuil);

                /** @var \Fines2\Persona_ */ $persona = $dataProvider->fetchEntityByUnique("persona", ["numero_documento"=>$cuilParts[1]]);

                if (empty($persona)) {
                    echo "No existe docente " . $cuil . "<br/>";
                    continue;
                } else {
                    echo "Ya existe docente en la base de datos " . $cuil . "<br/>";
                }

                $persona->cuil = implode("", $cuilParts);
                $modifyQueries->buildUpdateKeySqlById($persona, "cuil");
            } else {
                echo "No hay match para $line <br>";
            }
            continue;
        }
    }

    foreach ($dias as $dia) {
        if (strpos($line, $dia) !== false) {
            // Extract values
            $comision_pfid = substr($line, 0, strpos($line, "/"));
            $asignatura_codigo = trim(substr($line, strpos($line, "/") + 1, strpos($line, " ") - strpos($line, "/") - 1));

            if (strlen($asignatura_codigo) > 5) {
                $asignatura_codigo = substr($asignatura_codigo, 0, 5);
            }

            $descripcion_horario = substr($line, strpos($line, $dia));
            if (in_array($comision_pfid, $pfidComisiones)) {
                echo "*****************************************<br/>";

                echo "Procesando comisión " . $comision_pfid. "<br/>";

                $id_curso = $pdoFines->idCursoByParams($comision_pfid, $asignatura_codigo, CALENDARIO_ID);
                if (empty($id_curso)) {
                    echo "No existe curso " . $comision_pfid . " " . $asignatura_codigo . "<br>";
                    break;
                }

                echo "Curso existente " . $comision_pfid . " " . $asignatura_codigo . " (" . $id_curso . ")<br>";
                echo "Voy a actualizar horario " . $descripcion_horario . "<br>";
                // Update descripcion_horario
                $result = $pdoFines->updateDescripcionHorarioById($descripcion_horario, $id_curso);
                if ($result) {
                    echo "Descripcion horario actualizada.". "<br/>";
                } else {
                    echo "Descripcion horario no actualizada (no existe ID o misma DESCRIPCION_HORARIO)<br/>";
                }
                $procesar_docente = true;
            }
            break;
        }
    }
      
}
  