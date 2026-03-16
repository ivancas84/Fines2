<?php

use App\Context;
use Fines2\DataAccess\TomaDAO;
use Fines2\Model\Persona_;

add_submenu_page(
    null, 
    'Administrar Docente',
    'Administrar Docente', 
    'edit_posts', 
    FINES_PLUGIN.'-ad3', 
    'ad3_page' // Función que muestra la página del submenu
  );

function ad3_page() {

    wp_page_message();
    $persona = ad3_init_persona();
    if($persona->_status < 0) return;

    ad3_init_tomas($persona);
    //ad_init_calificaciones($persona);
    //ad_init_detalles($persona);

}

function ad3_init_persona(): Persona_{
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;


    /** @var Persona_ */ $persona = new Persona_();
    if(!empty($persona_id)) $persona->initById($persona_id);

    include plugin_dir_path(__FILE__) . 'ad3_persona_form_html.php';

    return $persona;


}



function ad3_init_tomas(Persona_ $persona){
    $dataProvider = Context::getFinesDb()->CreateDataProvider();
    $estados = TomaDAO::estados();
    $tiposMovimientos = TomaDAO::tiposMovimientos();
    $estadosContralor = TomaDAO::estadosContralor();
    $estadosContralor = TomaDAO::estadosContralor();

    /** @var Toma_[] */ $tomas = $dataProvider->fetchAllEntitiesByParams("toma", ["docente" => $persona->id], ["fecha_toma" => "DESC"]);
    
    if ($tomas) {
        include plugin_dir_path(__FILE__) . 'ad3_tomas_table_form_html.php';
    } else {
        echo "<p>No hay tomas asignadas.</p>";
    }
}
