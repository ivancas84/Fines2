<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Calificacion_;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    $id = $_POST['alumno_comision_id'];
    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
    $modifyQueries->deleteSqlById("alumno_comision",$id);
    $modifyQueries->process();
    ValueTypesUtils::redirect("Se ha eliminado un alumno de la comisión");

} catch (Exception $ex){
    ValueTypesUtils::redirect($ex->getMessage());
}


