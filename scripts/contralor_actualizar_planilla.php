<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once '../fines-config.php';

use \SqlOrganize\Sql\DbMy;
use \Fines2\Toma_;
use \Fines2\TomaDAO;

$calendario_id = CALENDARIO_ID_ACTUAL;
$planilla_id = PLANILLA_ID;

$db = \App\Context::getFinesDb();

$tomas = TomaDAO::TomasContralorByCalendario($calendario_id);
if(!count($tomas)){
    echo "No hay tomas pendientes para contralor del calendario " . $calendario_id;
    die();
}

foreach($tomas as $toma){
    
    if(empty($toma->planilla_docente))
        echo "UPDATE toma SET planilla_docente = '" . $planilla_id  . "' WHERE id = '" . $toma->id ."';<br>";
    else{
        $observaciones = empty($toma->observaciones) ? "" : $toma->observaciones;
        $observaciones .= " " . "Tiene Reclamo " . date("Y-m");  
        echo "UPDATE toma SET reclamo = false, observaciones = '" . $observaciones . "' WHERE id = '" . $toma->id ."';<br>";

    }   
}