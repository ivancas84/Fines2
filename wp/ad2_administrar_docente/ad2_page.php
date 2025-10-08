<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

use \Fines2\Persona_;
use \Fines2\Calificacion_;
use \Fines2\CalificacionDAO;
use \Fines2\Alumno_;
use \Fines2\AlumnoComision_;

use \Fines2\AlumnoDAO;
use \Fines2\DetallePersona_;
use Fines2\Toma_;
use Fines2\TomaDAO;
use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;

add_submenu_page(
    null, 
    'Administrar Docente',
    'Administrar Docente', 
    'edit_posts', 
    'fines-plugin-ad2', 
    'ad2_page' // Función que muestra la página del submenu
  );

function ad2_page() {

    wp_page_message();
    $persona = ad2_init_persona();
    if($persona->_status < 0) return;

    ad2_init_tomas($persona);
    //ad_init_calificaciones($persona);
    //ad_init_detalles($persona);

}

function ad2_init_persona(): Persona_{
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;


    /** @var Persona_ */ $persona = new Persona_();
    if(!empty($persona_id)) $persona->initById($persona_id);

    include plugin_dir_path(__FILE__) . 'ad2_persona_form_html.php';

    return $persona;


}



function ad2_init_tomas(Persona_ $persona){
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
    $estados = TomaDAO::estados();
    $tiposMovimientos = TomaDAO::tiposMovimientos();
    $estadosContralor = TomaDAO::estadosContralor();
    $estadosContralor = TomaDAO::estadosContralor();

    /** @var Toma_[] */ $tomas = $dataProvider->fetchAllEntitiesByParams("toma", ["docente" => $persona->id], ["fecha_toma" => "DESC"]);
    
    if ($tomas) {
        include plugin_dir_path(__FILE__) . 'ad2_tomas_table_form_html.php';
    } else {
        echo "<p>No hay tomas asignadas.</p>";
    }
}
