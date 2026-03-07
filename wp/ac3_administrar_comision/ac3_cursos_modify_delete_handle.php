<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

add_action('admin_post_ac3_cursos_modify_delete', 'ac3_cursos_modify_delete_handle');

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\Curso_;
use SqlOrganize\Utils\ValueTypesUtils;

function ac3_cursos_modify_delete_handle() {
    try {
        $comision_id = wp_initialize_handle("fines-plugin-ac3", "ac3_cursos_modify_delete_handle", "comision_id");
        
        $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();

        //si el campo delete_index esta definido se realizara la eliminación
        if(!empty($_POST["delete_index"])){
            $i = $_POST["delete_index"];
            $curso_id = $_POST["curso_id" . $i];
            $modifyQueries->buildDeleteSqlById("curso", $curso_id);
            $modifyQueries->execute();
            wp_redirect_handle("fines-plugin-ac3", "comision_id", $comision_id, "Curso eliminado");
            exit;
        } 

        $i = 0;

        while (isset($_POST["curso_id$i"])) {
            $cursoData = ValueTypesUtils::filterArrayBySuffix($_POST, $i);

            $curso = new Curso_();
            $curso->initById($cursoData["curso_id"]);
            $curso->setFromArray($cursoData);
            $curso->update();
            $i++;
        }


        wp_redirect_handle("fines-plugin-ac3", "comision_id", $comision_id, "Cursos modificados");

    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ac3", "comision_id", $comision_id, $ex->getMessage());
    }
}
