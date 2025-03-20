<?php
add_action('admin_post_ac_curso_delete', 'ac_curso_delete_handle');

function ac_curso_delete_handle() {

    $comision_id = $_POST['comision_id'];

    if (!current_user_can('edit_posts')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision&comision_id=$comision_id&message=No tienes permisos suficentes"));
        exit;
    }

    if (!isset($_POST['ac_curso_delete_nonce']) || !wp_verify_nonce($_POST['ac_curso_delete_nonce'], 'ac_curso_delete_action')) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision&comision_id=$comision_id&message=Error de seguridad"));
        exit;
    }

    if (!empty($_POST['honeypot'])) {
        wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision&comision_id=$comision_id&message=Detección de spam"));
        exit;
    }

    $wpdb = fines_plugin_db_connect();

    
    
    <td><input type="text" name="asignatura_detalle" value="<?= esc_attr($curso->asignatura_detalle) ?>"></td>
    <td><input type="text" name="horas_catedra" value="<?= esc_attr($curso->horas_catedra) ?>"></td>
    <td><?= $curso->disposicion_horas_catedra) ?></td>
    <td><input type="text" name="descripcion_horario" value="<?= esc_attr($curso->descripcion_horario) ?>"></td>
    <td>


    $curso_id = sanitize_or_null_text_field($_POST['curso_id']);
    $asignatura_detalle = sanitize_or_null_text_field($_POST['asignatura_detalle']);
    $horas_catedra = sanitize_or_null_text_field($_POST['horas_catedra']);
    $descripcion_horario = sanitize_or_null_text_field($_POST['descripcion_horario']);

    $wpdb->update(
        "curso",
        [
            'asignatura_detalle' => $asignatura_detalle,
            'horas_catedra' => $horas_catedra,
            'descripcion_horario' => $descripcion_horario,
        ],
        ['id' => $curso_id]
    );

    wp_redirect(admin_url("admin.php?page=fines-plugin-administrar-comision&comision_id=$comision_id&message=Curso modificado"));
    exit;
}
