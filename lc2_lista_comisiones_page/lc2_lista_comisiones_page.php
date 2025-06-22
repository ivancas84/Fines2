<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\DataAccess\SedeDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

/*
add_menu_page(
    'Administración Fines', //Título de la Página
    'Fines', // Título del menú
    'edit_posts', // Permisos
    'fines-plugin', // Slug del menú
    'lc2_lista_comisiones_page', // Función que muestra la página principal del plugin
    'dashicons-admin-generic', // Icono del menú
    2 // Posición en el menú

);*/



add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Comisiones 2', // Título de la página
    'Comisiones 2', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-lc2',  // Slug del submenú
    'lc2_lista_comisiones_page' // Función que muestra la página del submenu
);

function lc2_lista_comisiones_page() {

    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();

	$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);

	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';
    $filter_autorizada = isset($_GET['autorizada']) ? true : false;
    
    include plugin_dir_path(__FILE__) . 'lc2_formulario_busqueda_html.php';

    if (isset($_GET['submit']) && !empty($_GET['calendario'])) {
            $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", $_POST, ["pfid" => "ASC"]);
            $ids_sedes = ValueTypesUtils::arrayOfName($comisiones, "sede");
            $referentesLabel = SedeDAO::referentesLabelByIdSedes($ids_sedes);
            
            if (!empty($comisiones)) {
                include plugin_dir_path(__FILE__) . 'lc2_tabla_comisiones.html';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    }

    echo "</div>";
}