<?php

/**
 * Recibe un array de alumno_comision de un determinado alumno y actualiza sus valores
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    $countActualizados = 0;
    $i = 0;
    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    while (isset($_POST["comision_id$i"])) {
        $acData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

        /** @var AlumnoComision_ */ $ac = $db->createEntityById("alumno_comision", $acData["comision_id"]);
        $ac->ssetFromArray($acData);
        if($ac->_status < 1){
            $ac->update($modifyQueries);
            $countActualizados++;
        }
        $i++;
    }

    $modifyQueries->process();
    ValueTypesUtils::redirect("$countActualizados comisiones de alumno actualizadas");


} catch (Exception $ex){
    ValueTypesUtils::redirect($ex->getMessage());
}


