<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

use \Fines2\Comision_;
use \Fines2\Calendario_;
use \Fines2\Sede_;
use \Fines2\Modalidad_;
use \Fines2\Planificacion_;
use \Fines2\Disposicion_;

use \SqlOrganize\Sql\DbMy;

add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Más Opciones', // Título de la página
    'Más Opciones', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-mo',  // Slug del submenú
    'mo_mas_opciones_page' // Función que muestra la página del submenu
);
  
function mo_mas_opciones_page() {

    wp_page_message();

    $comision = (empty($comision_id)) ? new Comision_(): DbMy::getInstance()->CreateDataProvider()->fetchEntityByParams("\Fines2\Comision_", ["id" =>$comision_id]);
    include plugin_dir_path(__FILE__) . 'mo_mas_opciones_page.html';
}


//include plugin_dir_path(__FILE__) . 'ac2_comision_form_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_modify_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_add_handle.php';