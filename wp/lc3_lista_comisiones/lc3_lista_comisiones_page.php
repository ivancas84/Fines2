<?php

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\DesignacionDAO;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    'fines6-plugin', //debe coincidir con el slug del menu
    'Comisiones', // Título de la página
    'Comisiones', //Título del menú
    'edit_posts', // Permisos
    'fines6-plugin-lc3',  // Slug del submenú
    'lc3_lista_comisiones_page' // Función que muestra la página del submenu
);

function lc3_lista_comisiones_page() {

    wp_page_message();

    $db = \App\Context::getFinesDb();

    $dataProvider = $db->CreateDataProvider();

	$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);

	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : $calendarios[0]->id;
    $filter_autorizada = isset($_GET['autorizada']) ? true : false;
    
    $params = [
        "calendario" => $selected_calendario,
    ];

    if($filter_autorizada) {
        $params["autorizada"] = true;
    }

    include plugin_dir_path(__FILE__) . 'lc3_formulario_busqueda_html.php';

    $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", $params, ["pfid" => "ASC"]);
    $cantidadAlumnos = AlumnoComisionDAO::cantidadAlumnosComisionCalendario($selected_calendario);
    $ids_sedes = ValueTypesUtils::arrayOfName($comisiones, "sede");
    $referentesLabel = DesignacionDAO::referentesLabelByIdSedes($ids_sedes);
    
    if (!empty($comisiones)) {
        include plugin_dir_path(__FILE__) . 'lc3_tabla_comisiones_html.php';
    } else {
        echo "<p>No se encontraron comisiones para este calendario.</p>";
    }
}