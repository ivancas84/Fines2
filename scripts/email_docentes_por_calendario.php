<?php

define("CALENDARIO_ID", "202502110007");
require_once '../db-config.php';

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
$sql = "SELECT DISTINCT CONCAT(persona.email, ', ', persona.email_abc) 
        FROM toma 
        INNER JOIN persona ON toma.docente = persona.id
        INNER JOIN curso ON (toma.curso = curso.id)
        INNER JOIN comision ON (curso.comision = comision.id)
        WHERE comision.calendario = :calendario ";
$emails = $dataProvider->fetchAllColumnSqlByParams($sql, 0, ["calendario"=>CALENDARIO_ID]);
echo implode(", ", $emails);
