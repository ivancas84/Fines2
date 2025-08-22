<?php

use Fines2\Toma_;
use SqlOrganize\Sql\DbMy;

add_action('admin_post_ac2_toma_add', 'ac2_toma_add_handle');

function ac2_toma_add_handle() {

    try {
        $comision_id = wp_initialize_handle("fines-plugin-ac2", "ac2_toma_add_handle", "comision_id");

        $docente = isset($_POST['dni_docente']) ? DbMy::getInstance()->CreateDataProvider()->fetchEntityByParams("persona", ["numero_documento" => $_POST['dni_docente']]) : null;
        if(empty($docente)) {
            throw new Exception("No se encontró el docente con el DNI proporcionado.");
        }
        $toma = new Toma_();
        $toma->ssetFromArray($_POST);
        $toma->setFk("docente", $docente);
        $toma->insert();
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, "Toma agregada");
        exit;
    } catch (Exception $ex) {
        wp_redirect_handle("fines-plugin-ac2", "comision_id", $comision_id, $ex->getMessage());
        exit;
    }
}
