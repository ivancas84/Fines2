<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\DesignacionDAO;
echo "<pre>";
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();


$calendarios = $dataProvider->fetchAllEntitiesByParams("\\Fines2\\Calendario_");
foreach($calendarios as $calendario){
    echo $calendario->getLabel();
    echo "<br>";    
}
