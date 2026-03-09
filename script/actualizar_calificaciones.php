<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Calificacion_;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

   $countActualizados = 0;
    $i = 0;
    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    while (isset($_POST["calificacion_id$i"])) {
        $calificacionData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

        $calificacion = new Calificacion_();
        $calificacion->initById($calificacionData["calificacion_id"]);
        $calificacion->ssetFromArray($calificacionData);
        if($calificacion->_status < 1){
            $calificacion->update($modifyQueries);
            $countActualizados++;
        }
        $i++;
    }

    $modifyQueries->process();
    ValueTypesUtils::redirect("$countActualizados calificaciones actualizadas");


} catch (Exception $ex){
    ValueTypesUtils::redirect($ex->getMessage());
}


