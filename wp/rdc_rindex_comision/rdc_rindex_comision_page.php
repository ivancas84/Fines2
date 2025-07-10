<?php



require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\DesignacionDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;



add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Rindex Comisión', // Título de la página
    'Rindex Comisión', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-rdc',  // Slug del submenú
    'lc2_lista_comisiones_page' // Función que muestra la página del submenu
);

function rdc_rindex_comision_page() {
    wp_page_message();

    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();

    $comision_id = $_GET["comision_id"];

    $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=>$comision_id, "activo"=>true]);

    $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision"=>$comision_id]);

	$calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);

	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : $calendarios[0]->id;
    $filter_autorizada = isset($_GET['autorizada']) ? true : false;
    
    $params = [
        "calendario" => $selected_calendario,
    ];

    if($filter_autorizada) {
        $params["autorizada"] = true;
    }

    include plugin_dir_path(__FILE__) . 'lc2_formulario_busqueda_html.php';

    $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", $params, ["pfid" => "ASC"]);
    $ids_sedes = ValueTypesUtils::arrayOfName($comisiones, "sede");
    $referentesLabel = DesignacionDAO::referentesLabelByIdSedes($ids_sedes);
    
    if (!empty($comisiones)) {
        include plugin_dir_path(__FILE__) . 'lc2_tabla_comisiones.html';
    } else {
        echo "<p>No se encontraron comisiones para este calendario.</p>";
    }
}