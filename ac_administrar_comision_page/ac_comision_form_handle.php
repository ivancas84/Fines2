<?php

add_action('wp_ajax_ac_comision_form_handle', 'ac_comision_form_handle'); // procesar formulario para usuarios autenticados
add_action('wp_ajax_nopriv_ac_comision_form_handle', 'ac_comision_form_handle'); // procesar formulario para usuarios no autenticados

function ac_comision_form_handle() {
    $wpdb = fines_plugin_db_connection();

    // Verificación de nonce de seguridad
    if (!isset($_POST['ac_comision_form_nonce']) || !wp_verify_nonce($_POST['ac_comision_form_nonce'], 'ac_comision_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }

    // Verificación del honeypot para detección de spam
    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }

    // Capturar los datos del formulario
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

    // Si $comision_id está vacío, insertar nueva comisión
    if (empty($comision_id)) {
        $comision_id = uniqid();
        $insert_result = $wpdb->insert(
            'comision', // Nombre de la tabla
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
            [
                '%s', // ID de la comisión
                '%s', '%s', '%s', '%s', // Calendario, sede, modalidad y planificación son cadenas
                '%s', '%s', '%s',       // Turno, división y pfid son cadenas
                '%d', '%d', '%d',       // Campos booleanos como enteros
                '%s',                   // Observaciones como texto
            ]
        );

        if ($insert_result !== false) {
            echo json_encode([
                'success' => true, 
                'message' => 'Comisión creada con éxito.',
                'comision_id' => $comision_id 
            ]); // Enviar el ID al front-end]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la comisión: ' .  $wpdb->last_error]);
        }
    } else {
        // Actualizar la comisión existente si se proporciona un ID
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
            [
                '%s', '%s', '%s', '%s',
                '%s', '%s', '%s',
                '%d', '%d', '%d',
                '%s'
            ],
            ['%d'] // Tipo de dato para el ID de la comisión
        );

        if ($update_result !== false) {
            echo json_encode([
                'success' => true, 
                'message' => 'Comisión actualizada con éxito.',
                'comision_id' => $comision_id // Enviar el ID al front-end
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al actualizar la comisión: ' . $wpdb->last_error,
            ]);
        }
    }

    die(); // Finalizar la ejecución para evitar cualquier salida adicional
}
