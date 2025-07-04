<?php

require_once __DIR__ . '/db-config.php';

use SqlOrganize\Sql\DbMy;

$db = DbMy::getInstance();

$personas = $db->CreateDataProvider()->fetchAllEntitiesByParams("persona", ["id" =>'10']);

echo "<pre>";

foreach($personas as $persona) {
    print_r($persona->domicilio_->toArray());
}

