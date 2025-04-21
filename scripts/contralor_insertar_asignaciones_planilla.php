<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';

require_once 'class/PdoFines.php';


$calendario_id = CALENDARIO_ID;
$planilla_docente_id = PLANILLA_DOCENTE_ID;

echo "Insertar asignaciones " . $calendario_id . " a " . $planilla_docente_id . "<br/>";

$pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
$tomas = $pdoFines->contralorByCalendario($calendario_id);

echo "Se insertarán " . count($tomas) . " tomas<br/>";
foreach($tomas as $toma){
    $pdoFines->insertAsignacionPlanillaDocente($toma->id, $planilla_docente_id);
}