<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \Fines2\TomaDAO;

$calendario_id = "202502110007";

$db = DbMy::getInstance();

$tomas = TomaDAO::TomasContralorByCalendario($calendario_id);
if(!count($tomas)){
    echo "No hay tomas pendientes para contralor del calendario " . $calendario_id;
    die();
}
echo "<table>";
foreach($tomas as $toma){

    $orientacion_ = explode(' ', $toma->curso_?->disposicion_?->planificacion_?->plan_?->orientacion);
    $orientacion = '';
    
    foreach ($orientacion_ as $word) {
        $orientacion .= mb_substr($word, 0, 1, 'UTF-8');
    }

    $codigo = preg_split('/[ ,]/', $toma->curso_?->disposicion_?->asignatura_?->codigo, 2)[0];

    $horas_catedra = !empty($toma->curso_?->horas_catedra) ? $toma->curso_?->horas_catedra : $toma->curso_?->disposicion_?->horas_catedra;

    echo "<tr>";
    echo "<td>S/N</td>";
    echo "<td>" . substr($toma->docente_?->cuil, 0, 2) . "</td>";
    echo "<td>" . $toma->docente_?->numero_documento . "</td>";
    echo "<td>".substr($toma->docente_?->cuil, -1)."</td>";
    echo "<td></td>";
    echo "<td>" . date('d/m/Y', strtotime($toma->docente_?->fecha_nacimiento?->format("Y-m-d"))) . "</td>";
    echo "<td>" . mb_strtoupper($toma->docente_?->apellidos, 'UTF-8') . ", " . mb_convert_case(mb_strtolower($toma->docente_?->nombres, 'UTF-8'), MB_CASE_TITLE, 'UTF-8') . "</td>";
    echo "<td>P</td>";

    echo "<td>" . mb_strtoupper($orientacion, 'UTF-8') . "</td>";
    echo "<td>" . $codigo. "</td>";
    echo "<td>" . $horas_catedra . "</td>";
    echo "<td>PF</td>";
    echo "<td>".$toma->curso_?->disposicion_?->planificacion_?->anio."</td>";
    echo "<td>".$toma->curso_?->disposicion_?->planificacion_?->semestre."</td>";
    echo "<td>" . mb_substr($toma->curso_?->comision_?->turno, 0, 1, 'UTF-8') . "</td>";
    echo "<td>" . $toma->tipo_movimiento . "</td>";
    echo "<td>" . date('d', strtotime($toma->fecha_toma?->format("Y-m-d"))) . "</td>";
    echo "<td>" . date('m', strtotime($toma->fecha_toma?->format("Y-m-d"))) . "</td>";
    echo "<td>" . date('y', strtotime($toma->fecha_toma?->format("Y-m-d"))) . "</td>";
    echo "<td>" . date('d', strtotime($toma->curso_?->comision_?->calendario_?->fin?->format("Y-m-d"))) . "</td>";
    echo "<td>" . date('m', strtotime($toma->curso_?->comision_?->calendario_?->fin?->format("Y-m-d"))) . "</td>";
    echo "<td>" . date('y', strtotime($toma->curso_?->comision_?->calendario_?->fin?->format("Y-m-d"))) . "</td>";

    echo "</tr>";

}
echo "</table>";