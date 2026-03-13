<?php

use App\Context;
use Fines2\Model\Comision_;

add_submenu_page(
    FINES_PLUGIN, //debe coincidir con el slug del menu
    'Más Opciones', // Título de la página
    'Más Opciones', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN.'-mo2',  // Slug del submenú
    'mo2_page' // Función que muestra la página del submenu
);
  
function mo2_page() {

    wp_page_message();

    include plugin_dir_path(__FILE__) . 'mo2_page_html.php';
}