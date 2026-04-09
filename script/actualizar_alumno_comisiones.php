<?php

//Eliminar todas las calificaciones del curso


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {

    $countActualizados = 0;
    $i = 0;
    /** @var Db */ $db = Context::getFinesDb();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    while (isset($_POST["alumno_comision_id$i"])) {
        $alumnoComisionData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

         /** @var AlumnoComision_ */ $alumnoComision = $db->createEntityById("alumno_comision", $alumnoComisionData["alumno_comision_id"]);
        $alumnoComision->ssetFromArray($alumnoComisionData);
        if($alumnoComision->_status < 1){
            $alumnoComision->update($modifyQueries);
            $countActualizados++;
        }
        $i++;
    }

    $modifyQueries->process();
    ValueTypesUtils::redirect("$countActualizados comisiones actualizadas");


} catch (Exception $ex){
    ValueTypesUtils::redirect($ex->getMessage());
}


