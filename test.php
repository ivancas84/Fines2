<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\SedeDAO;
echo "<pre>";

$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["id" => ['089929c7-97df-4f06-8437-2e1bcc512945', "67cb7b00e0347"]], ["division"=>"ASC", "planificacion" => "ASC", "calendario" => "ASC"]);

$ids_sedes = ValueTypesUtils::arrayOfName($comisiones, "sede");
$referentesLabel = SedeDAO::referentesLabelByIdSedes($ids_sedes);

print_r($referentesLabel);
