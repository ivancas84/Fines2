<?php

require_once '../db-config.php';

use \SqlOrganize\Sql\DbMy;
use Fines2\TomaDAO;

$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$id_sede_dgcye = '202410081921';
$id_calendario = '202502110007';
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$tomas = TomaDAO::TomasActivasBySedeAndCalendario($id_sede_dgcye, $id_calendario);

$emails = [];
foreach ($tomas as $toma){
    if(!empty($toma->docente_->email) && !in_array($toma->docente_->email, $tomas))
        array_push($emails, $toma->docente_->email);

    if(!empty($toma->docente_->email_abc) && !in_array($toma->docente_->email_abc, $tomas))
        array_push($emails, $toma->docente_->email_abc);
        
}


echo implode(", ", $emails);