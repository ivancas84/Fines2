<?php

namespace SqlOrganize\Sql\Fines2;

require_once __DIR__ . '/config.php';

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\Fines2\Schema;
use SqlOrganize\Sql\Fines2\Persona;


$schema = new Schema();

$db = DbMy::getInstance($configDb, $schema);

$toma = $db->CreateDataProvider()->fetchTreeByIds("persona", '10');
echo "<pre>";
print_r($toma);

$persona = new Persona($db);

echo "<pre>";
print_r($persona);