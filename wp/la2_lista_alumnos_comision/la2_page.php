<?php

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\DesignacionDAO;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision;
use Fines2\Model\Comision_;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganize\Sql\Db;
use SqlOrganize\Utils\ValueTypesUtils;
session_start();

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

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    $comision_id = $_GET["comision_id"];

    /** @var Comision_ */  $comision = $db->createEntityById("comision", $comision_id);

	/** @var AlumnoComision_ */ $alumnos_comision = AlumnoComisionDAO::alumnosComision($comision_id);

    if (!empty($alumnos_comision)) {
        include plugin_dir_path(__FILE__) . 'la2_tabla_alumnos_comision_html.php';
    } else {
        echo "<p>No se encontraron alumnos para esta comision.</p>";
    }

    if(!empty($_SESSION["PHPSESS"])){
        $pf = new PfDAO( $_SESSION['PHPSESS']);

        echo "voy a consultar la lista de alumnos";
        $lista_alumnos = $pf->getListaAlumnos($comision->pfid);

        echo "<pre>";
        print_r($lista_alumnos);
    }
}