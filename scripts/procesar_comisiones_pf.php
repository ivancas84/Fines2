<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once 'includes/db_config.php';
require_once 'includes/queries_fines.php';
require_once 'class/PdoFines.php';;

$pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);

$pfidComisiones = $pdoFines->pfidsComisionesByCalendario(CALENDARIO_ID);

// Path to your JSON file
$file = RUTA_BASE . '/comisiones.txt';
$dataText = file_get_contents($file);

$dict = []; // Equivalent to Dictionary<string, object>
$procesar_docente = false;
$dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes"]; // Assuming these are the days

foreach (array_filter(explode(PHP_EOL, $dataText)) as $line) {
    
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
                $id = $pdoFines->idPersonaByDni($cuilParts[1] ?? '');

                if (empty($id)) {
                    echo "No existe docente " . $cuil . "<br/>";
                    continue;
                } else {
                    echo "Ya existe docente en la base de datos " . $cuil . "<br/>";
                }

                // Update cuil
                $result = $pdoFines->updateCuilById(implode("", $cuilParts), $id);
                if ($result) {
                    echo "CUIL actualizado.". "<br/>";
                } else {
                    echo "CUIL no actualizado (no existe ID o mismo CUIL)." . "<br/>";
                }
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
?>
