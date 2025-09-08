<?php

require_once __DIR__ . '/main-config.php';

use SqlOrganize\Sql\DbMy;

$db = \App\Context::getFinesDb();

$personas = $db->CreateDataProvider()->fetchAllEntitiesByParams("persona", ["id" =>'10']);

echo "<pre>";

foreach($personas as $persona) {
    print_r($persona->domicilio_->toArray());
}

