<?php

define("CALENDARIO_ID", "202502110007");
require_once __DIR__ . '/db-config.php';

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \SqlOrganize\Utils\ValueTypesUtils;
use \Fines2\Comision_;
use \Fines2\DesignacionDAO;
use Fines2\Persona_;
use SqlOrganize\Sql\ModifyQueries;

echo "<pre>";
$db = DbMy::getInstance();
$dataProvider = $db->CreateDataProvider();
$row = [
    "id" => "6087762195862",
    "numero_documento" => "31073351",
    "cuil" => "",
    "email" => "palmieriagustina954@gmail.com",
    "email_abc" => "", 
];

$persona = new Persona_();
$persona->initByUnique($row);
echo "<pre>";
print_r($persona->toArray());
