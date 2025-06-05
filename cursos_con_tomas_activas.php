<?php


require_once __DIR__ . '/db-config.php';

use SqlOrganize\Sql\DbMy;
use Fines2\CursoDAO;



$db = DbMy::getInstance();



$personas = $db->CreateDataProvider()->fetchEntitiesByIds("persona", '10');

echo "<pre>";

foreach($personas as $persona) {
    print_r($persona->domicilio_->toArray());
}

