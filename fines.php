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

    add_submenu_page(
      'fines-plugin', //debe coincidir con el slug del menu
      'Administrar Comisión', // Título de la página
      'Administrar Comisión', //Título del menú
      'edit_posts', // Permisos
      'fines-plugin-administrar-comision-page',  // Slug del submenú
      'ac_administrar_comision_page' // Función que muestra la página del submenu
  );

  add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Buscar Personas', // Título de la página
    'Buscar Personas', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-buscar-personas-page',  // Slug del submenú
    'bp_buscar_personas_page' // Función que muestra la página del submenu
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
		'fines-plugin-detalle-persona-page', 
		'dp_detalle_persona_page');

    add_submenu_page(
      null, 
      'Generar Comisiones Siguientes del Semestre',
      'Generar Comisiones Siguientes del Semestre', 
      'edit_posts', 
      'fines-plugin-comisiones-siguientes-semestre', 
        'fines_plugin_comisiones_siguientes_semestre');

      add_submenu_page(
        null, 
        'Rindex división',
        'Rindex división', 
        'edit_posts', 
        'fines-plugin-rindex-division-page', 
        'rd_rindex_division_page');

        add_submenu_page(
          null, 
          'Informe alumnos división',
          'Informe alumnos división', 
          'edit_posts', 
          'fines-plugin-detalle-persona-division-page', 
          'dpd_detalle_persona_division_page');
  }

include_once plugin_dir_path(__FILE__) . 'includes/helpers.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_db_connection.php';

include_once plugin_dir_path(__FILE__) . 'includes/queries.php';

include_once plugin_dir_path(__FILE__) . 'lc_lista_comisiones_page/lc_lista_comisiones_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_lista_alumnos_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_comisiones_siguientes_semestre.php';

include_once plugin_dir_path(__FILE__) . 'dp_detalle_persona_page/dp_detalle_persona_page.php';

include_once plugin_dir_path(__FILE__) . 'ac_administrar_comision_page/ac_administrar_comision_page.php';

include_once plugin_dir_path(__FILE__) . 'rd_rindex_division_page/rd_rindex_division_page.php';

include_once plugin_dir_path(__FILE__) . 'dpd_detalle_persona_division_page/dpd_detalle_persona_division_page.php';

include_once plugin_dir_path(__FILE__) . 'bp_buscar_personas_page/bp_buscar_personas_page.php';
