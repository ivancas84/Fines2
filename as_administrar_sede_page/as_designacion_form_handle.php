<?php

add_action('wp_ajax_as_designacion_form_handle', 'as_designacion_form_handle');
add_action('wp_ajax_nopriv_as_designacion_form_handle', 'as_designacion_form_handle');

function as_designacion_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['as_designacion_form_nonce']) || !wp_verify_nonce($_POST['as_designacion_form_nonce'], 'as_designacion_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }
    
    $sede_id = sanitize_or_null_text_field($_POST['sede_id']);
    $cargo = sanitize_or_null_text_field($_POST['cargo']);
    $numero_documento = sanitize_or_null_text_field($_POST['numero_documento']);

    $persona = wpdbPersona__By_numeroDocumento($wpdb, $numero_documento);
    if(!$persona){
        echo json_encode(['success' => false, 'message' => 'La persona no existe.']);
    }

    $insert_result = $wpdb->insert('designacion', [
        'id' => uniqid(),
        'desde' => date("Y-m-d"),
        'cargo' => $cargo,
        'sede' => $sede_id,
        'persona' => $persona->id,
    ], ['%s', '%s', '%s', '%s', '%s']);

    if(!$insert_result) {
        echo json_encode(['success' => false, 'message' => 'Error al insertar designacion: ' .  $wpdb->last_error]);
        die();
    }

    echo json_encode([ 'success' => true, 'message' => 'Registro completo.', 'sede_id' => $sede_id ]);
    die();
}
