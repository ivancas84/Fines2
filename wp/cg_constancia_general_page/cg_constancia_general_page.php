<?php


function cg_constancia_general_page() {
    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;

    $nombres = "";
    $apellidos = "";  
    $numero_documento = "";

    $fecha = fechaActualDiaDeMesDeAnio();
    $titulo = "CONSTANCIA DE VACANTE";
    $contenido = "TIENE UNA VACANTE EN ESTE ESTABLECIMIENTO.";
    $presentacion = "Quien corresponda";
    $observaciones = "";
	
	
    include plugin_dir_path(__FILE__) . 'cg_constancia_general_form.html';
}


