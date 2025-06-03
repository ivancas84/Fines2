<?php

namespace SqlOrganize\Sql\Fines2;

require_once __DIR__ . '/config.php';
require_once MAIN_PATH . 'SqlOrganizeMy/Sql/DbMy.php';
require_once MAIN_PATH . 'Model/Schema.php';

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\Fines2\Schema;

$schema = new Schema();

$db = DbMy::getInstance($configDb, $schema);


$dataProvider = $db->CreateDataProvider();
$data = $dataProvider->fetchTreeByIds("toma", '00c0c5fe-670c-471d-93f7-d9f5fb473eb3');
echo "<pre>";
print_r($data);