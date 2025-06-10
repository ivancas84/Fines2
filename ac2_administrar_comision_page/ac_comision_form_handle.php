<?php

add_action('wp_ajax_ac_comision_form_handle', 'ac_comision_form_handle');
add_action('wp_ajax_nopriv_ac_comision_form_handle', 'ac_comision_form_handle');

function ac_comision_form_handle() {
    $wpdb = fines_plugin_db_connect();
    
    if (!isset($_POST['ac_comision_form_nonce']) || !wp_verify_nonce($_POST['ac_comision_form_nonce'], 'ac_comision_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }
    
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }
    
    $calendario_id = sanitize_text_field($_POST['calendario']);
    $sede_id = sanitize_text_field($_POST['sede']);
    $modalidad_id = sanitize_text_field($_POST['modalidad']);
    $planificacion_id = sanitize_text_field($_POST['planificacion']);
    $turno = sanitize_text_field($_POST['turno']);
    $division = sanitize_text_field($_POST['division']);
    $pfid = sanitize_text_field($_POST['pfid']);
    $autorizada = isset($_POST['autorizada']) ? 1 : 0;
    $apertura = isset($_POST['apertura']) ? 1 : 0;
    $publicada = isset($_POST['publicada']) ? 1 : 0;
    $observaciones = sanitize_textarea_field($_POST['observaciones']);
    $comision_id = !empty($_POST['comision_id']) ? sanitize_text_field($_POST['comision_id']) : null;

    $proceso = "";
    if (empty($comision_id)) {
        $proceso = "Inserción";
        $comision_id = uniqid();
        $insert_result = $wpdb->insert(
            'comision',
            [
                'id' => $comision_id,
                'calendario' => $calendario_id,
                'sede' => $sede_id,
                'modalidad' => $modalidad_id,
                'planificacion' => $planificacion_id,
                'turno' => $turno,
                'division' => $division,
                'pfid' => $pfid,
                'autorizada' => $autorizada,
                'apertura' => $apertura,
                'publicada' => $publicada,
                'observaciones' => $observaciones,
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s']
        );
        if(!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Error al crear la comisión: ' .  $wpdb->last_error]);
            die();
        }
    } else {
        $proceso = "Actualización";
        $update_result = $wpdb->update(
            'comision',
            [
                'calendario' => $calendario_id,
                'sede' => $sede_id,
                'modalidad' => $modalidad_id,
                'planificacion' => $planificacion_id,
                'turno' => $turno,
                'division' => $division,
                'pfid' => $pfid,
                'autorizada' => $autorizada,
                'apertura' => $apertura,
                'publicada' => $publicada,
                'observaciones' => $observaciones,
            ],
            ['id' => $comision_id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s'],
            ['%s']
        );
        
        if(!$update_result) {
            if(!empty($wpdb->last_error)){
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la comisión: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    // Insertar curso si no existe
    $disposiciones = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, asignatura, horas_catedra FROM disposicion WHERE planificacion = %s",
            $planificacion_id
        ),
        ARRAY_A
    );
    
    foreach ($disposiciones as $disposicion) {
        $curso_existente = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM curso WHERE comision = %s AND disposicion = %s",
                $comision_id,
                $disposicion['id']
            )
        );

        if ($curso_existente == 0) {
            $curso_id = uniqid();
            $insert_result = $wpdb->insert(
                'curso',
                [
                    'id' => $curso_id,
                    'horas_catedra' => $disposicion['horas_catedra'],
                    'comision' => $comision_id,
                    'disposicion' => $disposicion['id'],
                    'asignatura' => $disposicion['asignatura'],
                ],
                ['%s', '%d', '%s', '%s', '%s']
            );
            if(!$insert_result) {
                echo json_encode(['success' => false, 'message' => 'Error al crear cursos: ' .  $wpdb->last_error]);
                die();
            }
        }
    }

    echo json_encode([ 'success' => true, 'message' => $proceso . ' completa.', 'comision_id' => $comision_id ]);
    die();
}
