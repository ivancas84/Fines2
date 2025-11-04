<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/fines-config.php');

use SqlOrganize\Sql\DataProvider;

add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Buscar Personas', // Título de la página
    'Buscar Personas', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-bp2',  // Slug del submenú
    'bp2_page' // Función que muestra la página del submenu
);

function bp2_page() {
    wp_page_message();

    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    include plugin_dir_path(__FILE__) . 'bp2_form_html.php';

    if (isset($_GET['submit']) && !empty($_GET['search'])) {
        $personas = Fines2\PersonaDAO::searchPersonas($search);

        if ($personas ) {
            include plugin_dir_path(__FILE__) . 'bp2_tabla_html.php';
        } else {
            echo "<p>No se encontraron personas.</p>";
        }
    }

}