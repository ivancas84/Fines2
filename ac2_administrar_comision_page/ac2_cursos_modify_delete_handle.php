<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

add_action('admin_post_ac2_cursos_modify_delete', 'ac2_cursos_modify_delete_handle');

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\Curso_;

function ac2_cursos_modify_delete_handle() {
    try {
    $comision_id = wp_initialize_handle("fines-plugin-ac2", "ac2_cursos_modify_delete_handle", "comision_id");
    
    $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
    //si el campo delete_index esta definido se realizara la eliminación
    if(!empty($_POST["delete_index"])){
        $i = $_POST["delete_index"];
        $curso_id = $_POST["curso_id" . $i ];

    }


    
    $curso_id = $_REQUEST("curso_id");

    /** @var Curso_ $curso */
    $curso = Entity::createFromId("curso", $curso_id);
    $curso->setFromArray($_POST);
    $curso->update();

    wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Curso modificado");

    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $ex->getMessage());
    }
}
