<?php

add_action('wp_ajax_ap_persona_form_handle', 'ap_persona_form_handle');
add_action('wp_ajax_nopriv_ap_persona_form_handle', 'ap_persona_form_handle');

function ap_persona_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['ap_persona_form_nonce']) || !wp_verify_nonce($_POST['ap_persona_form_nonce'], 'ap_persona_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }
    
    $nombres = sanitize_text_field($_POST['nombres']);
    $apellidos = sanitize_text_field($_POST['apellidos']);
    $fecha_nacimiento = sanitize_text_field($_POST['fecha_nacimiento']);
    $numero_documento = sanitize_text_field($_POST['numero_documento']);
    $cuil = sanitize_text_field($_POST['cuil']);
    $genero = sanitize_text_field($_POST['genero']);
    $telefono = sanitize_text_field($_POST['telefono']);
    $email = sanitize_email($_POST['email']);
    $email_abc = sanitize_email($_POST['email_abc']);
    $lugar_nacimiento = sanitize_text_field($_POST['lugar_nacimiento']);
    $persona_id = !empty($_POST['persona_id']) ? sanitize_text_field($_POST['persona_id']) : null;

    $proceso = "";
    if (empty($persona_id)) {
        $proceso = "Inserción";
        $persona_id = uniqid();
        $insert_result = $wpdb->insert(
            'persona',
            [
                'id' => $persona_id,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $fecha_nacimiento,
                'numero_documento' => $numero_documento,
                'cuil' => $cuil,
                'genero' => $genero,
                'telefono' => $telefono,
                'email' => $email,
                'email_abc' => $email_abc,
                'lugar_nacimiento' => $lugar_nacimiento,
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        if(!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Error al crear la persona: ' .  $wpdb->last_error]);
            die();
        }
    } else {
        $proceso = "Actualización";
        $update_result = $wpdb->update(
            'persona',
            [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $fecha_nacimiento,
                'numero_documento' => $numero_documento,
                'cuil' => $cuil,
                'genero' => $genero,
                'telefono' => $telefono,
                'email' => $email,
                'email_abc' => $email_abc,
                'lugar_nacimiento' => $lugar_nacimiento,
            ],
            ['id' => $persona_id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'],
            ['%s']
        );
        
        if(!$update_result) {
            if(!empty($wpdb->last_error)){
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la persona: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'persona_id' => $persona_id ]);
    die();
}
