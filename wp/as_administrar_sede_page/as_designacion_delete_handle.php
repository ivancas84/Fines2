<?php
add_action('admin_post_designacion_delete', 'as_designacion_delete_handle');

function as_designacion_delete_handle() {

    if (!current_user_can('edit_posts')) {
        wp_die(__('No tienes permisos suficientes.'));
    }

    /*if (!isset($_POST['as_designacion_delete_nonce']) || !wp_verify_nonce($_POST['as_designacion_delete_nonce'], 'as_designacion_delete_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }*/

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    $designacion_id = $_POST['designacion_id'];
    $sede_id = $_POST['sede_id'];

    $wpdb->delete('designacion', ['id' => $designacion_id]);

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Designación eliminada"));
    exit;
}
