<?php

add_action('wp_ajax_as_domicilio_form_handle', 'as_domicilio_form_handle');
add_action('wp_ajax_nopriv_as_domicilio_form_handle', 'as_domicilio_form_handle');

function as_domicilio_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['as_domicilio_form_nonce']) || !wp_verify_nonce($_POST['as_domicilio_form_nonce'], 'as_domicilio_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }
    
    $calle = sanitize_or_null_text_field($_POST['calle']);
    $numero = sanitize_or_null_text_field($_POST['numero']);
    $entre = sanitize_or_null_text_field($_POST['entre']);
    $barrio = sanitize_or_null_text_field($_POST['barrio']);
    $localidad = sanitize_or_null_text_field($_POST['localidad']);
    $domicilio_id = sanitize_or_null_text_field($_POST['domicilio_id']);
    $sede_id = sanitize_or_null_text_field($_POST['sede_id']);

    $proceso = "";
    if (empty($domicilio_id)) {
        $proceso = "Inserción";
        $domicilio_id = uniqid();
        $insert_result = $wpdb->insert(
            'domicilio',
            [
                'id' => $domicilio_id,
                'calle' => $calle,
                'numero' => $numero,
                'entre' => $entre,
                'barrio' => $barrio,
                'localidad' => $localidad,
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );
        if(!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Error al crear domicilio: ' .  $wpdb->last_error]);
            die();
        }
    } else {
        $proceso = "Actualización";
        $update_result = $wpdb->update(
            'domicilio',
            [
                'calle' => $calle,
                'numero' => $numero,
                'entre' => $entre,
                'barrio' => $barrio,
                'localidad' => $localidad,
            ],
            ['id' => $domicilio_id],
            ['%s', '%s', '%s', '%s', '%s'],
            ['%s']
        );
        
        if(!$update_result) {
            if(!empty($wpdb->last_error)){
                echo json_encode(['success' => false, 'message' => 'Error al actualizar domicilio: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    $update_result = $wpdb->update(
        'sede',
        [
            'domicilio' => $domicilio_id,
        ],
        ['id' => $sede_id],
        ['%s'],
        ['%s']
    );

    if(!$update_result) {
        if(!empty($wpdb->last_error)){
            echo json_encode(['success' => false, 'message' => 'Error al actualizar sede: ' .  $wpdb->last_error]);
            die();
        }
    }

    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'sede_id' => $sede_id ]);
    die();
}
