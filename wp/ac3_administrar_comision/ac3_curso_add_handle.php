<?php

use Fines2\Curso_;

add_action('admin_post_ac3_curso_add', 'ac3_curso_add_handle');

function ac3_curso_add_handle() {

    try {
        $comision_id = wp_initialize_handle("fines-plugin-ac3", "ac3_curso_add_handle", "comision_id");

        $curso = new Curso_();
        $curso->comision = $comision_id;
        $curso->setFromArray($_POST);
        $curso->insert();
        wp_redirect_handle("fines-plugin-ac3", "comision_id", $comision_id, "Curso agregado");
        exit;
    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ac3", "comision_id", $comision_id, $ex->getMessage());
        exit;
    }
}
