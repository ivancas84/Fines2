<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Alumno_;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    $_POST["tiene_certificado"] = array_key_exists("tiene_certificado", $_POST) ? $_POST["tiene_certificado"] : false;
    $_POST["tiene_constancia"] = array_key_exists("tiene_constancia", $_POST) ? $_POST["tiene_constancia"] : false;
    $_POST["tiene_dni"] = array_key_exists("tiene_dni", $_POST) ? $_POST["tiene_dni"] : false;
    $_POST["tiene_partida"] = array_key_exists("tiene_partida", $_POST) ? $_POST["tiene_partida"] : false;
    $_POST["confirmado_direccion"] = array_key_exists("confirmado_direccion", $_POST) ? $_POST["confirmado_direccion"] : false;
    $_POST["previas_completas"] = array_key_exists("previas_completas", $_POST) ? $_POST["previas_completas"] : false;
    $_POST["id"] = $_POST["alumno_id"];
    $_POST["persona"] = $_POST["persona_id"];

    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
    /** @var Alumno_ */ $alumno = $db->createEntityByUnique("alumno", $_POST);
    $alumno->resetAndCheck();

    $message = $alumno->persistByStatus($modifyQueries);

    $modifyQueries->process();

    ValueTypesUtils::redirect($message);

} catch (Exception $ex){
    echo $ex->getMessage();
    ValueTypesUtils::redirect($ex->getMessage());
}



        

