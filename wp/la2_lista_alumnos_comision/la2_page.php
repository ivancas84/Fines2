<?php

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\DesignacionDAO;
use SqlOrganize\Sql\Db;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Comisiones', // Título de la página
    'Comisiones', //Título del menú
    'edit_posts', // Permisos
    'fines6-plugin-la2',  // Slug del submenú
    'la2_page' // Función que muestra la página del submenu
);

function la2_page() {

    wp_page_message();

    /** @var Db */ $db = \App\Context::getFinesDb();

    /** @var DataProvider */$dataProvider = $db->CreateDataProvider();

    $comision_id = $_GET["comision_id"];

    $comision = $db->createEntityById("comision", $comision_id);

	$alumnos_comision = AlumnoComisionDAO::alumnosComision($comision_id);

    if (!empty($alumnos_comision)) {
        include plugin_dir_path(__FILE__) . 'la2_tabla_alumnos_comision_html.php';
    } else {
        echo "<p>No se encontraron alumnos para esta comision.</p>";
    }
}