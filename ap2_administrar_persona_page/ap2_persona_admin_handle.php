<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PersonaDAO.php');


add_action('admin_post_ap2_persona_admin', 'ap2_persona_admin_handle');

function ap2_persona_admin_handle() {
    $persona_id = wp_initialize_handle("fines-plugin-ap2", "ap2_persona_admin", "persona_id");

    sanitize_or_null_text_fields_from_array($_POST, ["nombres", "apellidos", "fecha_nacimiento", "numero_documento", "cuil", "genero", "telefono", "lugar_nacimiento", "persona_id"]);
    sanitize_or_null_email_fields_from_array($_POST, ["email", "email_abc"]);

    try {
        if(!empty($persona_id)){
            PdoFines::UpdateFields_("persona", PersonaDAO::getFieldsPersona(), $_POST);
        } else {
            $_POST["persona_id"] = uniqid();
            PdoFines::InsertFields_("persona", PersonaDAO::getFieldsPersona(), $_POST);
        }
    } catch (Exception $e) {
        $message = "Error al modificar la persona: " . $e->getMessage();
        wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=" . urlencode($message)));
        exit;
    }

    wp_redirect(admin_url("admin.php?page=fines-plugin-ap2&persona_id=$persona_id&message=Registro realizado"));
    exit;
}