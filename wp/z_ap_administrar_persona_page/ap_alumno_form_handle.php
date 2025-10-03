<?php

add_action('wp_ajax_ap_alumno_form_handle', 'ap_alumno_form_handle');
add_action('wp_ajax_nopriv_ap_alumno_form_handle', 'ap_alumno_form_handle');

function ap_alumno_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['ap_alumno_form_nonce']) || !wp_verify_nonce($_POST['ap_alumno_form_nonce'], 'ap_alumno_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }

    if (empty($_POST['persona'])) {
        echo json_encode(['success' => false, 'message' => 'Id persona no definido.']);
        die();
    }
    
    
    // Sanitize inputs
    $fields = [
        'estado_inscripcion', 'plan', 'anio_ingreso', 'semestre_ingreso', 'anio_inscripcion',
        'semestre_inscripcion', 'establecimiento_inscripcion', 'observaciones', 'persona'
    ];
    
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : null;
    }
    
    // Sanitize checkboxes (convert to 1 or 0)
    $checkboxes = ['tiene_dni', 'tiene_constancia', 'tiene_certificado', 'previas_completas', 'tiene_partida', 'confirmado_direccion'];
    
    foreach ($checkboxes as $checkbox) {
        $data[$checkbox] = isset($_POST[$checkbox]) ? 1 : 0;
    }
    

    $alumno_id = !empty($_POST['alumno_id']) ? sanitize_text_field($_POST['alumno_id']) : null;
    $proceso = "";
    
    if (empty($alumno_id)) {
        $proceso = "Inserción";
        $alumno_id = uniqid();
        $data['id'] = $alumno_id;
        
        $insert_result = $wpdb->insert('alumno', $data);
        
        if (!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Error al crear el alumno: ' . $wpdb->last_error]);
            die();
        }
    } else {
        $proceso = "Actualización";
        
        $update_result = $wpdb->update('alumno', $data, ['id' => $alumno_id]);
        
        if (!$update_result && !empty($wpdb->last_error)) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el alumno: ' . $wpdb->last_error]);
            die();
        }
    }
    
    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'persona_id' => $data["persona"] ]);
    die();
}
