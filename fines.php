<?php
/**
 * Plugin Name: Fines
 * Plugin URI: https://planfines2.com.ar/
 * Description: Acceso a base de Administración fines
 * Version: 4.0
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
  add_shortcode('fines_toma_posesion', 'tp_toma_posesion_page');
}
add_action('init', 'register_fines_shortcodes');

add_action('admin_menu', 'fines_plugin_menu'); //function fines_plugin_menu to display the menu

// Remove the WordPress admin bar
add_filter('admin_footer_text', '__return_empty_string');
add_filter('update_footer', '__return_empty_string', 11);

// Enqueue the Dashicons style for the admin area
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('dashicons');
});

function fines_plugin_menu() {
	
    add_menu_page(
      'Administración Fines', //Título de la Página
      'Fines', // Título del menú
      'edit_posts', // Permisos
      'fines-plugin', // Slug del menú
      'lc2_lista_comisiones_page', // Función que muestra la página principal del plugin
      'dashicons-admin-generic', // Icono del menú
      1 // Posición en el menú
    );

    include_once plugin_dir_path(__FILE__) . 'lc2_lista_comisiones_page/lc2_lista_comisiones_page.php';

    include_once plugin_dir_path(__FILE__) . 'lcu_lista_cursos_page/lcu_lista_cursos_page.php';
    
    include_once plugin_dir_path(__FILE__) . 'ap2_administrar_persona_page/ap2_administrar_persona_page.php';

    include_once plugin_dir_path(__FILE__) . 'ac2_administrar_comision_page/ac2_administrar_comision_page.php';

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
    'Lista de Sedes', // Título de la página
    'Lista de Sedes', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-lista-sedes-page',  // Slug del submenú
    'ls_lista_sedes_page' // Función que muestra la página del submenu
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
    'fines-plugin', //debe coincidir con el slug del menu
    'Generar Constancia Vacante', // Título de la página
    'Generar Constancia Vacante', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-constancia-vacante-page',  // Slug del submenú
    'cv_constancia_vacante_page' // Función que muestra la página del submenu
  );
  
  add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Generar Constancia General', // Título de la página
    'Generar Constancia General', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-constancia-general-page',  // Slug del submenú
    'cg_constancia_general_page' // Función que muestra la página del submenu
  );
  
  add_submenu_page(
    'fines-plugin', 
    'Procesar Docentes PF',
    'Procesar Docentes PF', 
    'edit_posts', 
    'fines-plugin-procesar-docentes-pf-page', 
    'pfpd_procesar_docentes_pf_page'
  );

  include_once plugin_dir_path(__FILE__) . 'ppc_procesar_planilla_calificacion_page/ppc_procesar_planilla_calificacion_page.php';

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
      'Toma de Posesión',
      'Toma de Posesión', 
      'edit_posts', 
      'fines-plugin-toma-posesion-page', 
      'tp_toma_posesion_page');

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
      'Constancia de certificado de título en trámite completo',
      'Constancia de certificado de título en trámite completo', 
      'edit_posts', 
      'fines-plugin-constancia-certificado-completo-page', 
      'ccc_constancia_certificado_completo_page'
    );

    add_submenu_page(
      null, 
      'Administrar Tomas de Comisión',
      'Administrar Tomas de Comision', 
      'edit_posts', 
      'fines-plugin-constancia-pase-page', 
      'cp_constancia_pase_page'
    );

    add_submenu_page(
      null, 
      'Constancia de Pase',
      'Constancia de Pase', 
      'edit_posts', 
      'fines-plugin-administrar-toma-comision', 
      'atc_administrar_toma_comision_page'
    );

    


    add_submenu_page(
      null, 
      'Transferir Alumnos Activos',
      'Transferir Alumnos Activos', 
      'edit_posts', 
      'fines-plugin-transferir-alumnos-activos-page', 
      'taa_transferir_alumnos_activos_page'
    );

    include_once plugin_dir_path(__FILE__) . 'mo_mas_opciones/mo_mas_opciones_page.php';

    include_once plugin_dir_path(__FILE__) . 'cac2_cargar_alumnos_comision/cac2_cargar_alumnos_comision_page.php';
  }

include_once plugin_dir_path(__FILE__) . 'ac2_administrar_comision_page/ac2_comision_admin_handle.php';
include_once plugin_dir_path(__FILE__) . 'ac2_administrar_comision_page/ac2_cursos_modify_delete_handle.php';
include_once plugin_dir_path(__FILE__) . 'ac2_administrar_comision_page/ac2_curso_add_handle.php';


include_once plugin_dir_path(__FILE__) . 'ap2_administrar_persona_page/ap2_persona_admin_handle.php';
include_once plugin_dir_path(__FILE__) . 'ap2_administrar_persona_page/ap2_alumno_admin_handle.php';


include_once plugin_dir_path(__FILE__) . 'includes/helpers.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_db_connect.php';

include_once plugin_dir_path(__FILE__) . 'includes/queries.php';


include_once plugin_dir_path(__FILE__) . 'lcd_lista_comisiones_direccion_page/lcd_lista_comisiones_direccion_page.php';

include_once plugin_dir_path(__FILE__) . 'ls_lista_sedes_page/ls_lista_sedes_page.php';

include_once plugin_dir_path(__FILE__) . 'la_lista_alumnos_page/la_lista_alumnos_page.php';

include_once plugin_dir_path(__FILE__) . 'includes/fines_plugin_comisiones_siguientes_semestre.php';

include_once plugin_dir_path(__FILE__) . 'dp_detalle_persona_page/dp_detalle_persona_page.php';

include_once plugin_dir_path(__FILE__) . 'ap_administrar_persona_page/ap_administrar_persona_page.php';


include_once plugin_dir_path(__FILE__) . 'as_administrar_sede_page/as_administrar_sede_page.php';

include_once plugin_dir_path(__FILE__) . 'rd_rindex_division_page/rd_rindex_division_page.php';

include_once plugin_dir_path(__FILE__) . 'rdd_rindex_division_direccion_page/rdd_rindex_division_direccion_page.php';

include_once plugin_dir_path(__FILE__) . 'dpd_detalle_persona_division_page/dpd_detalle_persona_division_page.php';

include_once plugin_dir_path(__FILE__) . 'bp_buscar_personas_page/bp_buscar_personas_page.php';

include_once plugin_dir_path(__FILE__) . 'car_constancia_alumno_regular_page/car_constancia_alumno_regular_page.php';

include_once plugin_dir_path(__FILE__) . 'cp_constancia_pase_page/cp_constancia_pase_page.php';

include_once plugin_dir_path(__FILE__) . 'ccc_constancia_certificado_completo_page/ccc_constancia_certificado_completo_page.php';

include_once plugin_dir_path(__FILE__) . 'cv_constancia_vacante_page/cv_constancia_vacante_page.php';

include_once plugin_dir_path(__FILE__) . 'cg_constancia_general_page/cg_constancia_general_page.php';

include_once plugin_dir_path(__FILE__) . 'taa_transferir_alumnos_activos_page/taa_transferir_alumnos_activos_page.php';

include_once plugin_dir_path(__FILE__) . 'atc_administrar_toma_comision_page/atc_administrar_toma_comision_page.php';

include_once plugin_dir_path(__FILE__) . 'pfpd_procesar_docentes_pf_page/pfpd_procesar_docentes_pf_page.php';


include_once plugin_dir_path(__FILE__) . 'tp_toma_posesion_page/tp_toma_posesion_page.php';



