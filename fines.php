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


function register_fines_shortcodes() {
  add_shortcode('fines_lista_comisiones', 'lcd_lista_comisiones_direccion_page');
  add_shortcode('fines_rindex_division', 'rdd_rindex_division_direccion_page');

}
add_action('init', 'register_fines_shortcodes');

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
      'Buscar Personas', // Título de la página
      'Buscar Personas', //Título del menú
      'edit_posts', // Permisos
      'fines-plugin-buscar-personas-page',  // Slug del submenú
      'bp_buscar_personas_page' // Función que muestra la página del submenu
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
    'Administrar Sede', // Título de la página
    'Administrar Sede', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-administrar-sede-page',  // Slug del submenú
    'as_administrar_sede_page' // Función que muestra la página del submenu
  );

  


    add_submenu_page(
		null, 
		'Lista de Alumnos',
		'Lista de Alumnos', 
		'edit_posts', 
		'fines-plugin-lista-alumnos', 
		'la_lista_alumnos_page');

    add_submenu_page(
		null, 
		'Detalle de Alumno',
		'Detalle de Alumno', 
		'edit_posts', 
		'fines-plugin-detalle-persona-page', 
		'dp_detalle_persona_page');

    add_submenu_page(
      null, 
      'Administrar Persona',
      'Administrar Persona', 
      'edit_posts', 
      'fines-plugin-administrar-persona-page', 
      'ap_administrar_persona_page');

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

          add_submenu_page(
            null, 
            'Rindex división dirección',
            'Rindex división dirección', 
            'edit_posts', 
            'fines-plugin-rindex-division-direccion-page', 
            'rdd_rindex_division_direccion_page');

    add_submenu_page(
      null, 
      'Constancia de alumno regular',
      'Constancia de alumno regular', 
      'edit_posts', 
      'fines-plugin-constancia-alumno-regular-page', 
      'car_constancia_alumno_regular_page'
    );

    add_submenu_page(
      null, 
      'Constancia de Pase',
      'Constancia de Pase', 
      'edit_posts', 
      'fines-plugin-constancia-pase-page', 
      'cp_constancia_pase_page'
    );


    add_submenu_page(
      null, 
      'Transferir Alumnos Activos',
      'Transferir Alumnos Activos', 
      'edit_posts', 
      'fines-plugin-transferir-alumnos-activos-page', 
      'taa_transferir_alumnos_activos_page'
    );


  }


include_once plugin_dir_path(__FILE__) . 'includes/helpers.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_db_connect.php';

include_once plugin_dir_path(__FILE__) . 'includes/queries.php';

include_once plugin_dir_path(__FILE__) . 'lc_lista_comisiones_page/lc_lista_comisiones_page.php';

include_once plugin_dir_path(__FILE__) . 'lcd_lista_comisiones_direccion_page/lcd_lista_comisiones_direccion_page.php';

include_once plugin_dir_path(__FILE__) . 'la_lista_alumnos_page/la_lista_alumnos_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_comisiones_siguientes_semestre.php';

include_once plugin_dir_path(__FILE__) . 'dp_detalle_persona_page/dp_detalle_persona_page.php';

include_once plugin_dir_path(__FILE__) . 'ap_administrar_persona_page/ap_administrar_persona_page.php';

include_once plugin_dir_path(__FILE__) . 'ac_administrar_comision_page/ac_administrar_comision_page.php';

include_once plugin_dir_path(__FILE__) . 'as_administrar_sede_page/as_administrar_sede_page.php';

include_once plugin_dir_path(__FILE__) . 'rd_rindex_division_page/rd_rindex_division_page.php';

include_once plugin_dir_path(__FILE__) . 'rdd_rindex_division_direccion_page/rdd_rindex_division_direccion_page.php';

include_once plugin_dir_path(__FILE__) . 'dpd_detalle_persona_division_page/dpd_detalle_persona_division_page.php';

include_once plugin_dir_path(__FILE__) . 'bp_buscar_personas_page/bp_buscar_personas_page.php';

include_once plugin_dir_path(__FILE__) . 'car_constancia_alumno_regular_page/car_constancia_alumno_regular_page.php';

include_once plugin_dir_path(__FILE__) . 'cp_constancia_pase_page/cp_constancia_pase_page.php';

include_once plugin_dir_path(__FILE__) . 'taa_transferir_alumnos_activos_page/taa_transferir_alumnos_activos_page.php';

