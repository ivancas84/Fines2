<?php

use App\Context;
use Fines2\Model\Toma_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';



try {
    $comision_id = $_POST["comision_id"];

    /** @var Db */ $db = Context::getFinesDb();
    /** @var DataProvider */ $dp = $db->CreateDataProvider();
    /** @var ModifyQueries */ $mq = $db->CreateModifyQueries();

    $docente = isset($_POST['dni_docente']) ? $dp->fetchEntityByUnique("persona", ["numero_documento" => $_POST['dni_docente']]) : null;
    if(empty($docente)) {
        throw new Exception("No se encontró el docente con el DNI proporcionado.");
    }
    $toma = new Toma_();
    $toma->ssetFromArray($_POST);
    $toma->setFk("docente", $docente);
    $toma->insert($mq);
    $mq->process();
    ValueTypesUtils::redirect("Toma Agregada");
    exit;
} catch (Exception $ex) {
    ValueTypesUtils::redirect($ex->getMessage());
    exit;
}
