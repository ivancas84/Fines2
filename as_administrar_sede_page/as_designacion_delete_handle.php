<?php
add_action('admin_post_delete_designacion', 'as_designacion_delete_handle');
function as_designacion_delete_handle() {
    if (!current_user_can('edit_options')) {
        wp_die(__('No tienes permisos suficientes.'));
    }

    $wpdb;

    $designacion_id = $_POST['designacion_id'];
    $sede_id = $_POST['sede_id'];

    $wpdb->delete('designacion', ['id' => $designacion_id]);

    wp_redirect(admin_url("admin.php?page=as_administrar_sede&sede_id=$sede_id&message=Designación eliminada"));
    exit;
}
