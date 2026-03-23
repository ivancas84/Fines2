<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Context;
use Fines2\Model\Toma_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;


try {

    $comision_id = $_REQUEST["comision_id"];
            
    /** @var Db */ $db = Context::getFinesDb();
    /** @var DataProvider */ $dp = $db->CreateDataProvider();

    //si el campo delete_toma_index esta definido se realizara la eliminación
    if($_POST["delete_toma_index"] != ""){
        $i = $_POST["delete_toma_index"];
        $toma_id = $_POST["toma_id" . $i];
        /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

        $modifyQueries->deleteSqlById("toma", $toma_id);
        $modifyQueries->execute();
        ValueTypesUtils::redirect("Toma Eliminada");
        exit;
    } 

    $i = 0;
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    while (isset($_POST["toma_id$i"])) {
        $tomaData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

        /** @var Toma_ */ $toma = $db->createEntityById("toma", $tomaData["toma_id"]);
        $toma->ssetFromArray($tomaData);
        
        if($_POST["dni_docente$i"] != $toma->docente_?->numero_documento) {
            $docente = $dp->fetchEntityByParams("persona", ["numero_documento" => $_POST["dni_docente$i"]]);
            if(empty($docente)) {
                throw new Exception("No se encontró el docente con el DNI proporcionado.");
            }
            $toma->setFk("docente", $docente);    
        }
        if($toma->_status < 1){
            $toma->update($modifyQueries);
        }
        $i++;
    }
    $modifyQueries->process();
    ValueTypesUtils::redirect("Tomas actualizadas");


} catch (Exception $ex) {
    ValueTypesUtils::redirect($ex->getMessage());
}
