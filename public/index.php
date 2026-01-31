<?php

require __DIR__ . '/../vendor/autoload.php';


use App\Context;

Context::initFinesDb();
Context::initPedidosDb();

$dbf = Context::getFinesDb();
/** @var Asignatura_[] */ $asignaturas = $dbf->CreateDataProvider()->fetchAllEntitiesByParams("asignatura");  

echo "<pre>";
foreach($asignaturas as $asignatura){
    print_r($asignatura->toArray());
}
echo "</pre>";