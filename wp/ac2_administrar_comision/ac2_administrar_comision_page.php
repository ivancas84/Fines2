<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

use \Fines2\Comision_;
use \Fines2\CalendarioDAO;
use Fines2\CursoDAO;
use \Fines2\DisposicionDAO;
use \Fines2\ModalidadDAO;
use \Fines2\PlanificacionDAO;
use \Fines2\SedeDAO;
use \Fines2\TomaDAO;
use \SqlOrganize\Sql\DbMy;

add_submenu_page(
    'fines-plugin', //debe coincidir con el slug del menu
    'Administrar Comisión', // Título de la página
    'Administrar Comisión', //Título del menú
    'edit_posts', // Permisos
    'fines-plugin-ac2',  // Slug del submenú
    'ac2_administrar_comision_page' // Función que muestra la página del submenu
);
  
function ac2_administrar_comision_page() {

        wp_page_message();
        $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

        $calendarios = CalendarioDAO::calendarios();
        $sedes = SedeDAO::sedes462();
        $modalidades = ModalidadDAO::modalidades();
        $planificaciones = PlanificacionDAO::planificaciones();

        $comision = (empty($comision_id)) ? new Comision_(): \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityByParams("comision", ["id" =>$comision_id]);
        include plugin_dir_path(__FILE__) . 'ac2_comision_form.html';

    if(!empty($comision)) {
        ac2_init_cursos($comision);
        ac2_init_tomas($comision);
    }

}

function ac2_init_cursos(Comision_ $comision) {
    $disposiciones = DisposicionDAO::disposicionesActuales();
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
    $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision"=>$comision->id]);
    if($cursos)
        include plugin_dir_path(__FILE__) . 'ac2_curso_table_form.html';
    else 
        echo "<p>No hay cursos para mostrar de la comisión</p>";
}

function ac2_init_tomas(Comision_ $comision) {
    $cursos = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesByParams("curso", ["comision"=>$comision->id]);
    $estados = TomaDAO::estados();
    $tiposMovimientos = TomaDAO::tiposMovimientos();
    $estadosContralor = TomaDAO::estadosContralor();
    $estadosContralor = TomaDAO::estadosContralor();

    $tomas = TomaDAO::TomasByComision($comision->id);
    if($tomas)
        include plugin_dir_path(__FILE__) . 'ac2_tomas_table_form_html.php';
    else 
        echo "<p>No hay tomas para mostrar de la comisión</p>";

    include plugin_dir_path(__FILE__) . 'ac2_toma_add_form_html.php';

}
