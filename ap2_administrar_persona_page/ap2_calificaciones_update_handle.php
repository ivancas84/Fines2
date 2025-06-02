<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PersonaDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/AlumnoDAO.php');


add_action('admin_post_ap2_calificaciones_update', 'ap2_calificaciones_update_handle');

function ap2_calificaciones_update_handle() {

    $persona_id = $_POST['persona_id'];
    $_POST["persona"] = $persona_id;

    $alumno_id = $_POST['alumno_id'];

    initialize_handle("fines-plugin-ap2", "ap2_calificaciones_update", "persona_id", $persona_id);

    $i = 0;
    while (array_key_exists("calificacion_id".($i+1), $_POST));

    
    } 

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