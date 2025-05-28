<?php

add_action('admin_post_ap2_persona_admin', 'ap2_persona_admin_handle');


function ap2_persona_admin_handle() {

    $persona_id = $_POST['persona_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=No tienes permisos suficientes"));
        exit;
    }

    if (!isset($_POST['ap2_persona_admin_nonce']) || !wp_verify_nonce($_POST['ap2_persona_admin_nonce'], 'ap2_persona_admin_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=Detección de spam"));
        exit;
    }


    $result = false;
    if ($result === false) {
        $message = "Error al modificar la persona, no esta implementado";
    } else {
        $message = "Curso modificado";
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&comision_id=$persona_id&message=" . urlencode($message)));
    exit;
}