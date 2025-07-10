<?php



require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\DesignacionDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;



add_action('admin_post_lc2_comision_delete', 'lc2_comision_delete_handle');


function lc2_comision_delete_handle() {

    try {
        $calendario_id = wp_initialize_handle("fines-plugin-lc2", "lc2_comision_delete", "calendario_id");
        $comision_id = $_POST["comision_id"];
        $db = DbMy::getInstance();
        $dataProvider = $db->CreateDataProvider();
        $modifyQueries = $db->CreateModifyQueries();
        $cursos = $dataProvider->fetchAllEntitiesByParams("curso",["comision" => $comision_id]);
        $idsCursos = ValueTypesUtils::arrayOfName($cursos, "id");
        $modifyQueries->buildDeleteSqlByIds("curso", ...$idsCursos);
        $modifyQueries->buildDeleteSqlById("comision", $comision_id);
        $modifyQueries->process();
        wp_redirect_handle("fines-plugin-lc2", "calendario_id", $calendario_id, "Registro eliminado");

    } catch (Exception $ex){
      wp_redirect_handle("fines-plugin-lc2", "calendario_id", $calendario_id, $ex->getMessage());

    }
}