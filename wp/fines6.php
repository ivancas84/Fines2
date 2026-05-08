<?php
/**
 * Plugin Name: Fines6
 * Plugin URI: https://planfines2.com.ar/
 * Description: Acceso a base de Administración fines versión 6
 * Version: 6.0
 * Author: Iván Castañeda
 * Author URI: https://planfines2.com.ar/
 * License: GPL2
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/v6/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/v6/vendor/autoload.php';
include_once plugin_dir_path(__FILE__) . 'includes/helpers.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_fines6_shortcodes() {
  add_shortcode('fines_toma_posesion', 'tp2_shortcode');
}

add_action('init', 'register_fines6_shortcodes');
include_once plugin_dir_path(__FILE__) . 'tp2_toma_posesion/tp2_shortcode.php';


add_action('admin_menu', 'fines6_plugin_menu'); //function fines_plugin_menu to display the menu

// Remove the WordPress admin bar
/*add_filter('admin_footer_text', '__return_empty_string');
add_filter('update_footer', '__return_empty_string', 11);
*/
// Enqueue the Dashicons style for the admin area
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('dashicons');
});

function fines6_plugin_menu() {
	
    add_menu_page(
      'Administración Fines 6', //Título de la Página
      'Fines 6', // Título del menú
      'edit_posts', // Permisos
      'fines6-plugin', // Slug del menú
      'lc3_lista_comisiones_page', // Función que muestra la página principal del plugin
      'dashicons-admin-generic', // Icono del menú
      1 // Posición en el menú
    );

    //se incluyen en el menu principal
    include_once plugin_dir_path(__FILE__) . 'bp3_buscar_personas/bp3_page.php';

    include_once plugin_dir_path(__FILE__) . 'lc3_lista_comisiones/lc3_lista_comisiones_page.php';

    include_once plugin_dir_path(__FILE__) . 'lcu3_lista_cursos/lcu3_page.php';

    include_once plugin_dir_path(__FILE__) . 'ac3_administrar_comision/ac3_administrar_comision_page.php';

    include_once plugin_dir_path(__FILE__) . 'aa4_administrar_alumno/aa4_page.php';

    include_once plugin_dir_path(__FILE__) . 'cc_cargar_cookie/cc_page.php';

    include_once plugin_dir_path(__FILE__) . 'mo2_mas_opciones/mo2_page.php';

    include_once plugin_dir_path(__FILE__) . 'ad3_administrar_docente/ad3_page.php';
    
    include_once plugin_dir_path(__FILE__) . 'trp2_transferir_persona/trp2_page.php';

    include_once plugin_dir_path(__FILE__) . 'rdd2_rindex_division_direccion/rdd2_page.php';

    include_once plugin_dir_path(__FILE__) . 'pfpc2_procesar_comisiones_pf/pfpc2_page.php';
    
    include_once plugin_dir_path(__FILE__) . 'pfpd3_procesar_docentes_pf/pfpd3_page.php';

    include_once plugin_dir_path(__FILE__) . 'cac3_cargar_alumnos_comision/cac3_page.php';

    include_once plugin_dir_path(__FILE__) . 'la2_lista_alumnos_comision/la2_page.php';

    

    /** NO SE INCLUYEN EN EL MENU PRINCIPAL */
    include_once plugin_dir_path(__FILE__) . 'ccc2_constancia_certificado_completo/ccc2_page.php';

    include_once plugin_dir_path(__FILE__) . 'cv2_constancia_vacante/cv2_page.php';

    include_once plugin_dir_path(__FILE__) . 'car3_constancia_alumno_regular/car3_page.php';

    include_once plugin_dir_path(__FILE__) . 'cp3_constancia_pase/cp3_page.php';



  }









