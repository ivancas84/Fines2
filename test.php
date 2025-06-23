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
$comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["page"=>"fines-plugin-lc2","calendario"=>"202502110007"], ["pfid" => "ASC"]);
foreach($comisiones as $comision) {
    print_r($comision->toArray());
}
$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);
$ids_sedes = ValueTypesUtils::arrayOfName($comisiones, "sede");
$referentesLabel = DesignacionDAO::referentesLabelByIdSedes($ids_sedes);