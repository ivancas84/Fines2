<?php
/**
 * Plugin Name: Fines
 * Plugin URI: https://planfines2.com.ar/
 * Description: Acceso a base de Administración fines
 * Version: 1.0
 * Author: Iván Castañeda
 * Author URI: https://planfines2.com.ar/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', 'fines_plugin_menu'); //function fines_plugin_menu to display the menu


function fines_plugin_menu() {
	add_menu_page(
        'Administración Fines', //Título de la Página
        'Fines', // Título del menú
        'edit_posts', // Permisos
        'fines-plugin', // Slug del menú
        'fines_plugin_comisiones_page', // Función que muestra la página principal del plugin
        'dashicons-admin-generic', // Icono del menú
		2 // Posición en el menú

    );
	
	add_submenu_page(
        'fines-plugin', //debe coincidir con el slug del menu
        'Comisiones', // Título de la página
        'Comisiones', //Título del menú
        'edit_posts', // Permisos
        'fines-plugin-comisiones',  // Slug del submenú
        'fines_plugin_comisiones_page' // Función que muestra la página del submenu
    );

	add_submenu_page(
        null, //debe coincidir con el slug del menu
        'Prueba Validación', // Título de la página
        'Prueba Validación', //Título del menú
        'edit_posts', // Permisos
        'fines-plugin-persona-form',  // Slug del submenú
        'fines_plugin_persona_form_page' // Función que muestra la página del submenu
    );

    add_submenu_page(
		null, 
		'Lista de Alumnos',
		'Lista de Alumnos', 
		'edit_posts', 
		'fines-plugin-lista-alumnos', 
		'fines_plugin_lista_alumnos_page');

    add_submenu_page(
		null, 
		'Detalle de Alumno',
		'Detalle de Alumno', 
		'edit_posts', 
		'fines-plugin-detalle-persona', 
		'fines_plugin_persona_form_page');
}

include_once plugin_dir_path(__FILE__) . 'includes/helpers.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_db_connection.php';

include_once plugin_dir_path(__FILE__) . 'includes/queries.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_comisiones_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_lista_alumnos_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_persona_form_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_persona_form_page_handle_persona_form.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_persona_form_page_handle_alumno_form.php';



