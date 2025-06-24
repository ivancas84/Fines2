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

$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario");

$comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["page"=>"fines-plugin-lc2","calendario"=>"202502110007"], ["pfid" => "ASC"]);

$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);
foreach($calendarios as $c) {
    print_r($c->toArray());
}