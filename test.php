<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;

$db = DbMy::getInstance();
$planes = $db->CreateDataProvider()->fetchAllEntities("plan");
echo "<pre>";

print_r($planes->toArray());

$comision = Entity::createById("\Fines2\Comision_", "67cb7b00e0347");
echo "ESTADO: " . $comision->_status . "<br>";
print_r($comision->toArray());
$comision->set("division","CCC");
echo "ESTADO: " . $comision->_status . "<br>";
print_r($comision->toArray());
$comision = new Comision_();
echo "ESTADO: " . $comision->_status . "<br>";
print_r($comision->toArray());
