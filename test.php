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
/** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
$cursos = $dataProvider->fetchAllEntitiesByParams("curso",["comision" => $comision_id]);
$idsCursos = ValueTypesUtils::arrayOfName($cursos, "id");
echo "cursos a eliminar";
print_r($idsCursos);
$modifyQueries->buildDeleteSqlByIds("curso", ...$idsCursos);
$modifyQueries->buildDeleteSqlById("comision", $comision_id);
echo $modifyQueries->getSql();
$modifyQueries->process();

print_r($modifyQueries->parameters);

