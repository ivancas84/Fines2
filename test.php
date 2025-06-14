<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;

$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider(); 
$comision = DbMy::getInstance()->CreateDataProvider()->fetchEntityById("comision", "67cb7b00e0347");

$disposiciones = $dataProvider->fetchEntitiesByParams("disposicion", ["planificacion" => $comision->planificacion]);
$idDisposiciones = ValueTypesUtils::arrayOfName($disposiciones, "id");

echo "<pre>";
echo "<h1>comision</h1>";

print_r($comision->toArray());

echo "<h1>idDisposiciones</h1>";
print_r($idDisposiciones);
echo "<h1>Cursos</h1>";
$cursos_existentes = $dataProvider->fetchEntitiesByParams("curso", ["comision" => $comision->id, "disposicion" => $idDisposiciones]);

foreach($cursos_existentes as $ce){
    print_r($ce->toArray());
}