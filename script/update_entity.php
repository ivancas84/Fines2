<?php

/**
 * Actualizar entidad de forma general
 */

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Context;
use Fines2\Model\Toma_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

/** @var Db */ $db = Context::getFinesDb();
/** @var Toma_ */ $toma = $db->createEntityByUnique($_REQUEST["entity_name"], $_REQUEST);
/** @var ModifyQueries */ $mq = $db->CreateModifyQueries();

echo "<h3>Modificar " . $_REQUEST["entity_name"] . "</h3>";

if($toma->_status < 1){
    $toma->update($mq);
    $mq->process();
    echo $toma->htmlChangeLog();
    ValueTypesUtils::redirect("Registro actualizado");
} else {
    ValueTypesUtils::redirect("No se realizaron modificaciones");

}
?>

