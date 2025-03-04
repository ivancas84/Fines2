<?php

add_action('wp_ajax_as_sede_form_handle', 'as_sede_form_handle');
add_action('wp_ajax_nopriv_as_sede_form_handle', 'as_sede_form_handle');

function as_sede_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['as_sede_form_nonce']) || !wp_verify_nonce($_POST['as_sede_form_nonce'], 'as_sede_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }
    
    $sede_id = sanitize_or_null_text_field($_POST['sede_id']);
    $centro_educativo = sanitize_or_null_text_field($_POST['centro_educativo']);
    $numero = sanitize_or_null_text_field($_POST['numero']);
    $nombre = sanitize_or_null_text_field($_POST['nombre']);
    $observaciones = sanitize_or_null_text_field($_POST['observaciones']);
    $pfid = sanitize_or_null_text_field($_POST['pfid']);
    $pfid_organizacion = sanitize_or_null_text_field($_POST['pfid_organizacion']);

    $proceso = "";
    if (empty($sede_id)) {
        $proceso = "Inserción";
        $sede_id = uniqid();
        $insert_result = $wpdb->insert(
            'sede',
            [
                'id' => $sede_id,
                'centro_educativo' => $centro_educativo,
                'numero' => $numero,
                'nombre' => $nombre,
                'observaciones' => $observaciones,
                'pfid' => $pfid,
                'pfid_organizacion' => $pfid_organizacion,
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        if(!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Error al crear la sede: ' .  $wpdb->last_error]);
            die();
        }
    } else {
        $proceso = "Actualización";
        $update_result = $wpdb->update(
            'sede',
            [
                'centro_educativo' => $centro_educativo,
                'numero' => $numero,
                'nombre' => $nombre,
                'observaciones' => $observaciones,
                'pfid' => $pfid,
                'pfid_organizacion' => $pfid_organizacion,
            ],
            ['id' => $sede_id],
            ['%s', '%s', '%s', '%s', '%s', '%s'],
            ['%s']
        );
        
        if(!$update_result) {
            if(!empty($wpdb->last_error)){
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la sede: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'sede_id' => $sede_id ]);
    die();
}
