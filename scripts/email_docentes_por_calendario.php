<?php

define("CALENDARIO_ID", "202502110007");
require_once '../db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\DesignacionDAO;
use Fines2\TomaDAO;
use SqlOrganize\Sql\ModifyQueries;

echo "<pre>";
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$comision_id = 'a199f325-7d76-496d-9467-0a79ccafe104';
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$tomas = TomaDAO::TomasActivasByCalendario(CALENDARIO_ID);

$emails = [];
foreach ($tomas as $toma){
    if(!empty($toma->docente_->email) && !in_array($toma->docente_->email, $tomas))
        array_push($emails, $toma->docente_->email);

    if(!empty($toma->docente_->email_abc) && !in_array($toma->docente_->email_abc, $tomas))
        array_push($emails, $toma->docente_->email_abc);
        
}


echo implode(", ", $emails);