<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Asignatura_;

Context::initFinesDb();

$dbf = Context::getFinesDb();
/** @var Asignatura_[] */ $asignaturas = $dbf->CreateDataProvider()->fetchAllEntitiesByParams("asignatura");  

echo "<pre>";
foreach($asignaturas as $asignatura){
    print_r($asignatura->toArray());
}
echo "</pre>";