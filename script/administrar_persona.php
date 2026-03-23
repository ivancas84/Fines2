<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    $persona_id = $_POST["persona_id"];
    $_POST["id"] = $persona_id;
    $persona = $db->createEntityByUnique("persona", $_POST);
    $persona->resetAndCheck();
    
    $message = $persona->persistByStatus($modifyQueries);

    $modifyQueries->process();
    ValueTypesUtils::redirect($message);

} catch (Exception $ex){
    echo $ex->getMessage();
    ValueTypesUtils::redirect($ex->getMessage());
}

