<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

use App\Context;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\Persona_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Constancia General', // Título de la página
    'Constancia General', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-cg2',  // Slug del submenú
    'cg2_page' // Función que muestra la página del submenu
);

function cg2_page() {
    wp_page_message();

    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;
  
    $db = Context::getFinesDb();
    /** @var Persona_ */ $persona = $db->CreateDataProvider()->fetchEntityByParams("persona", ["id" => $persona_id]);

    $nombres = "";
    $apellidos = "";
    $numero_documento = "";
    if($persona){
        $nombres = esc_attr( mb_strtoupper($persona->nombres, 'UTF-8')) ?? "";
        $apellidos =  esc_attr( mb_strtoupper($persona->apellidos, 'UTF-8')) ?? "";
        $numero_documento = $persona->numero_documento ?? "";
    }
        
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
    
    
    include plugin_dir_path(__FILE__) . 'car2_html.php';
}


