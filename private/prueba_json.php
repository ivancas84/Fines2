<?php

//Definir sql para transferir alumnos aprobados

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';


use Fines2\Model\Comision_;
use App\Context;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\ModifyQueries;

try {
    
    /** @var Db */ $dbf = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider = $dbf->CreateDataProvider();

    /** @var Comision_[] */ $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario" => CALENDARIO_ID_ANTERIOR]);
    echo "Cantidad comisiones " . count($comisiones) . "<br>";

    /** @var EntityMetadata */ $entityMetadata;
    foreach($dbf->entitiesMetadata as $entityMetadata){
        //echo "Nombre " . $entityMetadata->name . "<br>";
    }
} catch (Exception $ex){
  echo $ex->getMessage();

}