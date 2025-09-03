<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

add_action('admin_post_ac2_tomas_modify_delete', 'ac2_tomas_modify_delete_handle');

use \SqlOrganize\Sql\DbMy;
use \Fines2\Curso_;
use Fines2\Toma_;
use SqlOrganize\Utils\ValueTypesUtils;

function ac2_tomas_modify_delete_handle() {
    try {
        $comision_id = wp_initialize_handle("fines-plugin-ac2", "ac2_tomas_modify_delete_handle", "comision_id");
        
        $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();

        //si el campo delete_toma_index esta definido se realizara la eliminación
        if($_POST["delete_toma_index"] != ""){
            $i = $_POST["delete_toma_index"];
            $toma_id = $_POST["toma_id" . $i];
            $modifyQueries->buildDeleteSqlById("toma", $toma_id);
            $modifyQueries->execute();
            wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Toma eliminada");
            exit;
        } 

        $i = 0;

        while (isset($_POST["toma_id$i"])) {
            $tomaData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);
            $toma = new Toma_();
            $toma->initById($tomaData["toma_id"]);
            $toma->ssetFromArray($tomaData);
            if($_POST["dni_docente$i"] != $toma->docente_?->numero_documento) {
                $docente = \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityByParams("persona", ["numero_documento" => $_POST["dni_docente$i"]]);
                if(empty($docente)) {
                    throw new Exception("No se encontró el docente con el DNI proporcionado.");
                }
                $toma->setFk("docente", $docente);    
            }
            if($toma->_status < 1){
                $toma->update();
            }
            $i++;
        }

        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Tomas modificadas");

    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $ex->getMessage());
    }
}
