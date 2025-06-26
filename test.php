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

$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);

/** @var Persona_  */ $persona = $dataProvider->fetchEntityByParams("persona", ["numero_documento" => "31073351"]);
/** @var Persona_  */ $personaCopy = clone $persona;

$personaCopy->nombres = "Roberto";
$personaCopy->apellidos = "Castañares";
$personaCopy->descripcion_domicilio = "Calle Falsa 123";
$response = $persona->compare($personaCopy);
print_r($response);

