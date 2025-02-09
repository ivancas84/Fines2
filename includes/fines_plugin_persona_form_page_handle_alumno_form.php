<?php

add_action('wp_ajax_handle_alumno_form', 'handle_alumno_form');
add_action('wp_ajax_nopriv_handle_alumno_form', 'handle_alumno_form');

function handle_alumno_form() {
    if (!isset($_POST['custom_alumno_form_nonce']) || !wp_verify_nonce($_POST['custom_alumno_form_nonce'], "custom_alumno_form_action")) {
        echo json_encode(["success" => false, "message" => "Nonce verification failed."]);
        wp_die();
    }

    global $wpdb;
    $alumno_id = sanitize_text_field($_POST['alumno_id']);
    $anio_ingreso = sanitize_text_field($_POST['anio_ingreso']);
    $estado_inscripcion = sanitize_text_field($_POST['estado_inscripcion']);
    $plan = sanitize_text_field($_POST['plan']);
    $fecha_titulacion = sanitize_text_field($_POST['fecha_titulacion']);
    $observaciones = sanitize_textarea_field($_POST['observaciones']);

    $updated = $wpdb->update(
        'alumno',
        [
            'anio_ingreso' => $anio_ingreso,
            'estado_inscripcion' => $estado_inscripcion,
            'plan' => $plan,
            'fecha_titulacion' => $fecha_titulacion,
            'observaciones' => $observaciones
        ],
        ['id' => $alumno_id],
        ['%s', '%s', '%s', '%s', '%s'],
        ['%s']
    );

    if ($updated !== false) {
        echo json_encode(["success" => true, "message" => "Datos del alumno actualizados correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar los datos."]);
    }
    
    wp_die();
}