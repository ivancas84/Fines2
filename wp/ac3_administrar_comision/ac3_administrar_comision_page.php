<?php

use App\Context;
use Fines2\DataAccess\CalendarioDAO;
use Fines2\DataAccess\DisposicionDAO;
use Fines2\DataAccess\ModalidadDAO;
use Fines2\DataAccess\PlanificacionDAO;
use Fines2\DataAccess\SedeDAO;
use Fines2\DataAccess\TomaDAO;
use Fines2\Model\Comision_;

add_submenu_page(
    FINES_PLUGIN, //debe coincidir con el slug del menu
    'Administrar Comisión', // Título de la página
    'Administrar Comisión', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN.'-ac3',  // Slug del submenú
    'ac3_administrar_comision_page' // Función que muestra la página del submenu
);
  
function ac3_administrar_comision_page() {

        wp_page_message();
        $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

        $calendarios = CalendarioDAO::calendarios();
        $sedes = SedeDAO::sedes462();
        $modalidades = ModalidadDAO::modalidades();
        $planificaciones = PlanificacionDAO::planificaciones();

        $comision = (empty($comision_id)) ? new Comision_(): Context::getFinesDb()->CreateDataProvider()->fetchEntityByParams("comision", ["id" =>$comision_id]);
        include plugin_dir_path(__FILE__) . 'ac3_comision_form_html.php';

    if(!empty($comision)) {
        ac3_init_cursos($comision);
        ac3_init_tomas($comision);
    }

}

function ac3_init_cursos(Comision_ $comision) {
    $disposiciones = DisposicionDAO::disposicionesActuales();
    $dataProvider = Context::getFinesDb()->CreateDataProvider();
    $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision"=>$comision->id]);
    if($cursos)
        include plugin_dir_path(__FILE__) . 'ac3_curso_table_form.html';
    else 
        echo "<p>No hay cursos para mostrar de la comisión</p>";
}

function ac3_init_tomas(Comision_ $comision) {
    $cursos = Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesByParams("curso", ["comision"=>$comision->id]);
    $estados = TomaDAO::estados();
    $tiposMovimientos = TomaDAO::tiposMovimientos();
    $estadosContralor = TomaDAO::estadosContralor();
    $estadosContralor = TomaDAO::estadosContralor();

    $tomas = TomaDAO::TomasByComision($comision->id);
    if($tomas)
        include plugin_dir_path(__FILE__) . 'ac3_tomas_table_form_html.php';
    else 
        echo "<p>No hay tomas para mostrar de la comisión</p>";

    include plugin_dir_path(__FILE__) . 'ac3_toma_add_form_html.php';

}
