<?php
/**
 * Plugin Name: Fines7
 * Plugin URI: https://planfines2.com.ar/
 * Description: Administracion Fines con acceso a datos por PDO.
 * Version: 0.1.0
 * Author: Ivan Castaneda
 * Author URI: https://planfines2.com.ar/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

$autoloadPath = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (!is_readable($autoloadPath)) {
    wp_die(esc_html__('No se encontro vendor/autoload.php dentro de Fines7. Ejecuta composer install en la carpeta del plugin.', 'fines7'));
}

require_once $autoloadPath;

\Fines7\Core\Plugin::init(__FILE__);
