<?php

use Fines2\DataAccess\PersonaDAO;

add_submenu_page(
    FINES_PLUGIN, //debe coincidir con el slug del menu
    'Buscar Personas', // Título de la página
    'Buscar Personas', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN.'-bp3',  // Slug del submenú
    'bp3_page' // Función que muestra la página del submenu
);

function bp3_page() {
    wp_page_message();

    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    include plugin_dir_path(__FILE__) . 'bp3_form_html.php';

    if (isset($_GET['submit']) && !empty($_GET['search'])) {
        $personas = PersonaDAO::searchPersonas($search);

        if ($personas ) {
            include plugin_dir_path(__FILE__) . 'bp3_tabla_html.php';
        } else {
            echo "<p>No se encontraron personas.</p>";
        }
    }

}