<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\DesignacionDAO;
use SqlOrganize\Sql\ModifyQueries;

echo "<pre>";
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$comision_id = 'a199f325-7d76-496d-9467-0a79ccafe104';
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$cursos = $dataProvider->fetchAllColumnByParams("toma", "estado", [], ["estado"=>"DESC"]);
echo "<pre>";
print_r($cursos);
