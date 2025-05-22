<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

add_menu_page(
    'Administración Fines', //Título de la Página
    'Fines', // Título del menú
    'edit_posts', // Permisos
    'fines-plugin', // Slug del menú
    'lc_lista_comisiones_page', // Función que muestra la página principal del plugin
    'dashicons-admin-generic', // Icono del menú
    2 // Posición en el menú

);

add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Comisiones', // Título de la página
    'Comisiones', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-comisiones',  // Slug del submenú
    'lc_lista_comisiones_page' // Función que muestra la página del submenu
);

function lc_lista_comisiones_page() {
    $pdo = new PdoFines();

	$calendarios = $pdo->calendarios();
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';
    $selected_order = isset($_GET['order_by']) ? sanitize_text_field($_GET['order_by']) : 'tramo';
    $filter_autorizada = isset($_GET['autorizada']) ? true : false;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lc_formulario_busqueda_html.php';

    if (isset($_GET['submit']) && !empty($_GET['calendario'])) {
            $calendario_id = sanitize_text_field($_GET['calendario']);
            
            $comisiones = $pdo->comisionesByParams($calendario_id, $filter_autorizada, esc_sql($selected_order));

            if ($comisiones) {
                include plugin_dir_path(__FILE__) . 'lc_tabla_comisiones.html';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    }

    echo "</div>";
}