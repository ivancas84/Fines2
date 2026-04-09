<?php

use App\Context;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Toma_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';



try {
    $alumno_id = $_POST["alumno_id"];
    $comision_id = $_POST["comision_id"];
    $estado = $_POST["estado"];

    /** @var Db */ $db = Context::getFinesDb();
    /** @var DataProvider */ $dp = $db->CreateDataProvider();
    /** @var ModifyQueries */ $mq = $db->CreateModifyQueries();

    $ac = new AlumnoComision_();
    $ac->sset("comision", $comision_id);
    $ac->sset("alumno", $alumno_id);
    $ac->sset("estado", $estado);
    $ac->insert($mq);
    $mq->process();
    ValueTypesUtils::redirect("Alumno agregado a comision");
    exit;
} catch (Exception $ex) {
    ValueTypesUtils::redirect($ex->getMessage());
    exit;
}
