<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'includes/queries_fines.php';


$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
$pdo->exec("SET NAMES 'utf8mb3'");

$id_calendario = "202502110007";

$tomas = pdoFines_ContralorByCalendario($pdo, $id_calendario);
echo "<table>";
foreach($tomas as $toma){
    $orientacion_ = explode(' ', $toma['orientacion']);
    $orientacion = '';
    
    foreach ($orientacion_ as $word) {
        $orientacion .= mb_substr($word, 0, 1, 'UTF-8');
    }

    $codigo = preg_split('/[ ,]/', $toma['asignatura_codigo'], 2)[0];

    $horas_catedra = !empty($toma['curso_horas_catedra']) ? $toma['curso_horas_catedra'] : $toma['disposicion_horas_catedra'];

    echo "<tr>";
    echo "<td>S/N</td>";
    echo "<td>".substr($toma['cuil'], 0, 2)."</td>";
    echo "<td>".$toma['numero_documento']."</td>";
    echo "<td>".substr($toma['cuil'], -1)."</td>";
    echo "<td></td>";
    echo "<td>" . date('d/m/Y', strtotime($toma['fecha_nacimiento'])) . "</td>";
    echo "<td>" . mb_strtoupper($toma['apellidos'], 'UTF-8') . ", " . mb_convert_case(mb_strtolower($toma['nombres'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8') . "</td>";
    echo "<td>P</td>";

    echo "<td>" . mb_strtoupper($orientacion, 'UTF-8') . "</td>";
    echo "<td>" . $codigo. "</td>";
    echo "<td>" . $horas_catedra . "</td>";
    echo "<td>PF</td>";
    echo "<td>".$toma['planificacion_anio']."</td>";
    echo "<td>".$toma['planificacion_semestre']."</td>";
    echo "<td>" . mb_substr($toma['turno'], 0, 1, 'UTF-8') . "</td>";
    echo "<td>".$toma['tipo_movimiento']."</td>";
    echo "<td>" . date('d', strtotime($toma['fecha_toma'])) . "</td>";
    echo "<td>" . date('m', strtotime($toma['fecha_toma'])) . "</td>";
    echo "<td>" . date('y', strtotime($toma['fecha_toma'])) . "</td>";
    echo "<td>" . date('d', strtotime($toma['calendario_fecha_fin'])) . "</td>";
    echo "<td>" . date('m', strtotime($toma['calendario_fecha_fin'])) . "</td>";
    echo "<td>" . date('y', strtotime($toma['calendario_fecha_fin'])) . "</td>";

    echo "</tr>";

}
echo "</table>";