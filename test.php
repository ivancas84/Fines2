<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;

$dataProvider = DbMy::getInstance()->CreateDataProvider();

$entities = $dataProvider->fetchEntitiesByParams("curso", ["comision"=>"67cb7b00e0347"]);

foreach($entities as $entity){
    print_r($entity->toArray());

}