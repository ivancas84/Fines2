<?php



require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\AlumnoDAO;
use Fines2\AlumnoComision_;
use Fines2\DesignacionDAO;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Lista alumnos comisión', // Título de la página
    'Lista alumnos comisión', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-lac',  // Slug del submenú
    'lac_lista_alumnos_comision_page' // Función que muestra la página del submenu
);

function lac_lista_alumnos_comision_page() {
    wp_page_message();

    $comision_id = $_GET["comision_id"];
    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();
    /** @var Comision_ */$comision = $dataProvider->fetchEntityByParams("comision", ["id"=>$comision_id]);
    /** @var AlumnoComision_[] */ $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=> $comision_id]);

    echo "<h3> Alumnos comisión " . $comision->getLabel() . "</h3>";
    if(empty($alumnosComision)){
        echo "La comisión no tiene alumnos cargados";
    } else {
        include plugin_dir_path(__FILE__) . 'lac_table_html.php';
    }
}