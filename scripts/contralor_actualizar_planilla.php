<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \Fines2\Toma_;

$calendario_id = "202502110007";
$planilla_id = 

$db = DbMy::getInstance();

$tomas = Toma_::TomasContralorByCalendario($calendario_id);
if(!count($tomas)){
    echo "No hay tomas pendientes para contralor del calendario " . $calendario_id;
    die();
}

foreach($tomas as $toma){
    
    if(empty($toma->planilla_docente))
        echo "UPDATE toma SET planilla_docente = '202506122023' WHERE id = '" . $toma->id ."';<br>";
    else{
        $observaciones = empty($toma->observaciones) ? "" : $toma->observaciones;
        $observaciones .= " " . "Tiene Reclamo " . date("Y-m");  
        echo "UPDATE toma SET reclamo = false, observaciones = '" . $observaciones . "' WHERE id = '" . $toma->id ."';<br>";

    }   
}