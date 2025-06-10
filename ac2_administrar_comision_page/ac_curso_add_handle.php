<?php
add_action('admin_post_ac_curso_add', 'ac_curso_add_handle');

function ac_curso_add_handle() {

  
    $comision_id = $_POST['comision_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision-page&comision_id=$comision_id&message=No tienes permisos suficientes"));
        exit;
    }

    if (!isset($_POST['ac_curso_add_nonce']) || !wp_verify_nonce($_POST['ac_curso_add_nonce'], 'ac_curso_add_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision-page&comision_id=$comision_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision-page&comision_id=$comision_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    // Generate a unique ID for the new curso
    $curso_id = uniqid('curso_');
    $disposicion = sanitize_or_null_text_field($_POST['disposicion']);
    $horas_catedra = sanitize_or_null_text_field($_POST['horas_catedra']);
    $descripcion_horario = sanitize_or_null_text_field($_POST['descripcion_horario']);

    $result = $wpdb->insert(
        "curso",
        [
            'id' => $curso_id,
            'comision' => $comision_id,
            'disposicion' => $disposicion,
            'horas_catedra' => $horas_catedra,
            'descripcion_horario' => $descripcion_horario,
        ]
    );

    if ($result === false) {
        $message = "Error al agregar el curso";
    } else {
        $message = "Curso agregado exitosamente";
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision-page&comision_id=$comision_id&message=" . urlencode($message)));
    exit;
}
