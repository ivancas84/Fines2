<?php

namespace SqlOrganize\Sql\Fines2;

require_once __DIR__ . '/config.php';

$dataProvider = $db->CreateDataProvider();
$data = $dataProvider->fetchTreeByIds("alumno", 'ff920472-c2e6-4f3e-99fc-4d8401260287', 'fe538c9d-1af6-4f5a-89dd-cdc757a51afe');
echo "<pre>";
print_r($data);