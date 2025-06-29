<?php

add_action('admin_post_as_add_designacion', 'as_designacion_form_handle');

function as_designacion_form_handle() {
    $wpdb = fines_plugin_db_connect();
    $sede_id = sanitize_or_null_text_field($_POST['sede_id']);
    
    if (!isset($_POST['as_designacion_form_nonce']) || !wp_verify_nonce($_POST['as_designacion_form_nonce'], 'as_designacion_form_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Error de seguridad"));
        exit;
    }
    
    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Detección de SPAM"));
        exit;
    }
    
    $cargo = sanitize_or_null_text_field($_POST['cargo']);
    $numero_documento = sanitize_or_null_text_field($_POST['numero_documento']);

    $persona = wpdbPersona__By_numeroDocumento($wpdb, $numero_documento);
    if(!$persona){
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=La persona no existe"));
        exit;
    }

    $insert_result = $wpdb->insert('designacion', [
        'id' => uniqid(),
        'desde' => date("Y-m-d"),
        'cargo' => $cargo,
        'sede' => $sede_id,
        'persona' => $persona->id,
    ], ['%s', '%s', '%s', '%s', '%s']);

    if(!$insert_result) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Error al insertar designación" .  $wpdb->last_error));
        exit;
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-sede-page&sede_id=$sede_id&message=Designación registrada"));
    exit;
}
