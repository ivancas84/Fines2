<?php

use App\Context;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Constancia Vacante',
    'Constancia Vacante', 
    'edit_posts', 
    FINES_PLUGIN.'-cv2', 
    'cv2_page'
);


function cv2_page() {
    wp_page_message();

    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;

    /** @var Db */ $db = Context::getFinesDb();
    /** @var Persona_ */ $persona = $db->CreateDataProvider()->fetchEntityByParams("persona", ["id" => $persona_id]);

    $nombres = "";
    $apellidos = "";
    $numero_documento = "";

    if($persona){
        $nombres = $persona->getNombres();
        $apellidos = $persona->getApellidos();
        $numero_documento = $persona->numero_documento;
    }

    
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
    $presentado = "Quien corresponda";
    $observaciones = "";
	
    include plugin_dir_path(__FILE__) . 'cv2_page_html.php';
}


