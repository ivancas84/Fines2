<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Fines2\Model\Comision_;
use Fines2\Model\Curso_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

$comision_id = $_POST["comision_id"];
    if(empty($comision_id)) 
    throw new Exception("No está definido el id de comisión");
    $db = \App\Context::getFinesDb();
    $comision = new Comision_();
    $comision->ssetFromArray($_POST);
    $comision->id = $comision_id;
    $comision->reset();

    if(!$comision->check()) 
        throw new Exception($comision->getLogging()->__toString());

    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
    $modifyQueries->persistSql($comision);

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    $disposiciones = $dataProvider->fetchAllEntitiesByParams("disposicion", ["planificacion" => $comision->planificacion]);
    $idDisposiciones = ValueTypesUtils::arrayOfName($disposiciones, "id");
    $cursosExistentes = $dataProvider->fetchAllEntitiesByParams("curso", ["comision" => $comision->id, "disposicion" => $idDisposiciones]);
    $cursosExistentes = ValueTypesUtils::dictOfObjByPropertyNames($cursosExistentes, "disposicion");

    $i = 0;
    foreach($disposiciones as $disposicion){
        if(array_key_exists($disposicion->id, $cursosExistentes))
            continue;

        $i++;
        $curso = new Curso_();
        $curso->setFk("comision", $comision);
        $curso->horas_catedra = $disposicion->horas_catedra;
        $curso->disposicion = $disposicion->id;
        $curso->reset();
        if(!$curso->check())
            throw new Exception($curso->getLogging()->__toString());
        $modifyQueries->insertSql($curso);
    }

    $modifyQueries->process();

    ValueTypesUtils::redirect("Datos registrados correctamente");

    } catch (Exception $ex){
        echo $ex->getMessage();
        ValueTypesUtils::redirect($ex->getMessage());
    }


