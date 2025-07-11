<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PersonaDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/AlumnoDAO.php');


add_action('admin_post_ap2_alumno_admin', 'ap2_alumno_admin_handle');

function ap2_alumno_admin_handle() {

    $persona_id = wp_initialize_handle("fines-plugin-ap2", "ap2_alumno_admin", "persona_id");
    $_POST["persona"] = $persona_id;

    $alumno_id = $_POST['alumno_id'];

    sanitize_or_null_text_fields_from_array($_POST, [
        "persona_id", "alumno_id", 'estado_inscripcion', 
        'plan', 'anio_ingreso', 
        'establecimiento_inscripcion', 'fecha_titulacion',
        'observaciones', 'persona', 'resolucion_inscripcion', 'adeuda_legajo','adeuda_deudores',
        'documentacion_inscripcion','libro_folio','libro','folio','comentarios']);

    intval_or_null_fields_from_array($_POST, ['anio_inscripcion', 'semestre_inscripcion', 'semestre_ingreso','anio_inscripcion_completo']);

    boolval_fields_from_array($_POST, [
        'tiene_dni', 'tiene_constancia', 
        'tiene_certificado', 'tiene_partida', 
        'previas_completas', 'confirmado_direccion']);

    try {
        if(!empty($alumno_id)){
            PdoFines::UpdateFields_("alumno", AlumnoDAO::getFields(), $_POST);
        } else {
            $_POST["alumno_id"] = uniqid();
            PdoFines::InsertFields_("persona", AlumnoDAO::getFields(), $_POST);
        }
    } catch (Exception $e) {
        $message = "Error al registrar alumno: " . $e->getMessage();
        wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=" . urlencode($message)));
        exit;
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=Registro realizado"));
    exit;
}