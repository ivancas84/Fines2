<?php

use App\Context;
use Fines2\Model\Alumno_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Cargar Cookie Programa Fines', // Título de la página
    'Cargar Cookie Programa Fines', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN . '-cc',  // Slug del submenú
    'cc_page' // Función que muestra la página del submenu
);


function cc_page() {
    wp_page_message();

    if (isset($_POST['submit']) && !empty($_POST['phpsess'])) {
        $_SESSION['PHPSESS'] = $_POST['phpsess'];
    }

    $phpsess = isset($_SESSION['PHPSESS']) ? $_SESSION['PHPSESS'] : null;

    include plugin_dir_path(__FILE__) . 'cc_html.php';

}


