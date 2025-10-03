<?php
add_action('admin_post_atc_toma_add', 'atc_toma_add_handle');

function atc_toma_add_handle() {

    $comision_id = $_POST['comision_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=No tienes permisos suficientes"));
        exit;
    }

    if (!isset($_POST['atc_toma_add_nonce']) || !wp_verify_nonce($_POST['atc_toma_add_nonce'], 'atc_toma_add_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    $numero_documento = sanitize_text_field($_POST['numero_documento']);

    // Buscar persona por número de documento
    $persona = $wpdb->get_row(
        $wpdb->prepare("SELECT id FROM persona WHERE numero_documento = %s", $numero_documento)
    );

    if (!$persona) {
        $message = "No se encontró una persona con el número de documento ingresado";
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=" . urlencode($message)));
        exit;
    }

    // Generate unique ID for new toma
    $toma_id = uniqid('toma_');
    $fecha_toma = sanitize_or_null_text_field($_POST['fecha_toma']);
    $estado = sanitize_or_null_text_field($_POST['estado']);
    $curso = sanitize_or_null_text_field($_POST['curso']);
    $tipo_movimiento = sanitize_or_null_text_field($_POST['tipo_movimiento']);
    $estado_contralor = sanitize_or_null_text_field($_POST['estado_contralor']);
    $calificacion = isset($_POST['calificacion']) ? 1 : 0;
    $confirmada = isset($_POST['confirmada']) ? 1 : 0;

    $result = $wpdb->insert(
        "toma",
        [
            'id' => $toma_id,
            'curso' => $curso,
            'fecha_toma' => $fecha_toma,
            'estado' => $estado,
            'tipo_movimiento' => $tipo_movimiento,
            'estado_contralor' => $estado_contralor,
            'calificacion' => $calificacion,
            'confirmada' => $confirmada,
            'docente' => $persona->id,

        ]
    );

    if ($result === false) {
        $message = "Error al agregar la toma";
    } else {
        $message = "Toma agregada exitosamente";
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=" . urlencode($message)));
    exit;
}
