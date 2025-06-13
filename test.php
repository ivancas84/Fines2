<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\Comision_;

$comision = DbMy::getInstance()->CreateDataProvider()->fetchEntityById("comision", "67cb7b00e0347");
$modifyQueries = DbMy::getInstance()->CreateModifyQueries();

$comision->observaciones = "quiero actualizar 2";
$modifyQueries->buildPersistSql($comision);
$modifyQueries->process();
$comision = DbMy::getInstance()->CreateDataProvider()->fetchEntityById("comision", "67cb7b00e0347");

print_r($comision->toArray());
