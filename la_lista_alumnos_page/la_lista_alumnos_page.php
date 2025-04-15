<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

function la_lista_alumnos_page() {



    if (!isset($_GET['comision_id']) || empty($_GET['comision_id'])) {
        echo "<p>Error: No se especificó la comisión.</p>";
        return;
    }

    $comision_id = $_GET['comision_id'];

    $pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);

    $comision = $pdoFines->comisionById($comision_id);
    $alumnos = $pdoFines->alumnosConCantidadCalificacionesAprobadasByComisionJoinAlumnoPlan($comision_id);

    // Define all possible "tramo" values
    $tramo_values = ["1°1C", "1°2C", "2°1C", "2°2C", "3°1C", "3°2C"];

    // Organize students' data
    $student_data = [];
    foreach ($alumnos as $alumno) {
        $student_id = $alumno->id;

        if (!isset($student_data[$student_id])) {

            $ingreso_color = '#FFFFFF'; // Default white
            if ($alumno->anio_ingreso == 1) {
                $ingreso_color = '#A3DAFF'; // Light blue pastel
            } elseif ($alumno->anio_ingreso == 2) {
                $ingreso_color = '#74BBFB'; // Blue pastel
            } elseif ($alumno->anio_ingreso == 3) {
                $ingreso_color = '#5A91E2'; // Dark blue pastel
            }


            $student_data[$student_id] = [
                'estado' => $alumno->estado,
                'anio_ingreso' => $alumno->anio_ingreso,
                'persona_id' => $alumno->persona_id,
                'ingreso' => '<td style="background-color: ' . $ingreso_color . ';">' . esc_html($alumno->anio_ingreso) . '</td>',
                'tiene_dni' => $alumno->tiene_dni ? '✔' : '✘',
                'tiene_certificado' => $alumno->tiene_certificado ? '✔' : '✘',
                'tiene_constancia' => $alumno->tiene_constancia ? '✔' : '✘',
                'previas_completas' => $alumno->previas_completas ? '✔' : '✘',
                'confirmado_direccion' => $alumno->confirmado_direccion ? '✔' : '✘',
                'tiene_partida' => $alumno->tiene_partida ? '✔' : '✘',
                'nombres' => $alumno->nombres,
                'apellidos' => $alumno->apellidos,
                'numero_documento' => $alumno->numero_documento,
                'tramos' => array_fill_keys($tramo_values, '0') // Default values for all tramos
            ];
        }

        if ($alumno->tramo && in_array($alumno->tramo, $tramo_values)) {
            $student_data[$student_id]['tramos'][$alumno->tramo] = $alumno->cantidad_aprobadas;
        }
    }

    // Display the commission information
    echo "<div class='wrap'>";
    echo "<h1>Lista de Alumnos - Comisión {$comision->pfid}</h1>";
    echo "<h3>{$comision->nombre} {$comision->tramo} {$comision->orientacion}</h3>";

    if (!empty($student_data)) {
      include plugin_dir_path(__FILE__) . 'la_alumnos_table.html';
    } else {
        echo "<p>No se encontraron alumnos para esta comisión.</p>";
    }

    echo "<a href='javascript:history.back();' class='button'>Volver</a>";
    echo "</div>";
}

function getTramoColor($value, $anio_ingreso, $tramo) {
    $gray = '#E0E0E0'; // Soft Gray for disabled cells

    // Determine if the cell should be gray
    $grayTramosForAnio2 = ['1°1C', '1°2C'];
    $grayTramosForAnio3 = ['1°1C', '1°2C', '2°1C', '2°2C'];

    if ($anio_ingreso == 3 && in_array($tramo, $grayTramosForAnio3)) {
        return $gray;
    } elseif ($anio_ingreso == 2 && in_array($tramo, $grayTramosForAnio2)) {
        return $gray;
    }

    // Normal color logic
    if ($value == 5) {
        return '#C8E6C9'; // Mild Green Pastel
    } elseif ($value == 4 || $value == 3) {
        return '#FFF5CC'; // Soft Yellow Pastel
    } else {
        return '#FFC9C9'; // Light Rose Pastel
    }
}