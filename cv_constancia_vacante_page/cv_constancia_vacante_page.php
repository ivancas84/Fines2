<?php


function cv_constancia_vacante_page() {
    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;
    $wpdb = fines_plugin_db_connect();

    $nombres = "";
    $apellidos = "";  
    $numero_documento = "";

    $fecha = fechaActualDiaDeMesDeAnio();
    $presentacion = "Quien corresponda";
    $observaciones = "";
	
	
    include plugin_dir_path(__FILE__) . 'cv_constancia_vacante_form.html';
}


