<?php
add_action('admin_post_atc_toma_modify', 'atc_toma_modify_handle');

function atc_toma_modify_handle() {

    $comision_id = $_POST['comision_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=No tienes permisos suficentes"));
        exit;
    }

    if (!isset($_POST['atc_toma_modify_nonce']) || !wp_verify_nonce($_POST['atc_toma_modify_nonce'], 'atc_toma_modify_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    $toma_id = sanitize_or_null_text_field($_POST['toma_id']);
    $fecha_toma = sanitize_or_null_text_field($_POST['fecha_toma']);
    $estado = sanitize_or_null_text_field($_POST['estado']);
    $tipo_movimiento = sanitize_or_null_text_field($_POST['tipo_movimiento']);
    $estado_contralor = sanitize_or_null_text_field($_POST['estado_contralor']);
    $calificacion = isset($_POST['calificacion']) ? 1 : 0; // Fix for undefined key
    $confirmada = isset($_POST['confirmada']) ? 1 : 0; // Fix for undefined key

    $wpdb->update(
        "toma",
        [
            'fecha_toma' => $fecha_toma,
            'estado' => $estado,
            'tipo_movimiento' => $tipo_movimiento,
            'estado_contralor' => $estado_contralor,
            'calificacion' => $calificacion,
            'confirmada' => $confirmada
        ],
        ['id' => $toma_id]
    );

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-toma-comision&comision_id=$comision_id&message=Toma modificada"));
    exit;
}
