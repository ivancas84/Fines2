<?php
add_action('admin_post_atc_toma_delete', 'atc_toma_delete_handle');

function atc_toma_delete_handle() {

    $comision_id = $_POST['comision_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=No tienes permisos suficientes"));
        exit;
    }

    if (!isset($_POST['atc_toma_delete_nonce']) || !wp_verify_nonce($_POST['atc_toma_delete_nonce'], 'atc_toma_delete_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    $toma_id = sanitize_text_field($_POST['toma_id']);

    $result = $wpdb->delete(
        "toma",
        ['id' => $toma_id]
    );

    if ($result === false) {
        $message = "Error al eliminar la toma";
    } else {
        $message = "Toma eliminada correctamente";
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=" . urlencode($message)));
    exit;
}
