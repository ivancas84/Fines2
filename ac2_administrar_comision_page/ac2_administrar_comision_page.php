<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

use \Fines2\Comision_;
use \Fines2\Calendario_;
use \Fines2\Sede_;
use \Fines2\Modalidad_;
use \Fines2\Planificacion_;
use \Fines2\Disposicion_;

use \SqlOrganize\Sql\DbMy;


add_submenu_page(
      'fines-plugin', //debe coincidir con el slug del menu
      'Administrar Comisión2', // Título de la página
      'Administrar Comisión2', //Título del menú
      'edit_posts', // Permisos
      'fines-plugin-ac2',  // Slug del submenú
      'ac2_administrar_comision_page' // Función que muestra la página del submenu
  );
  
    function ap2_administrar_persona_page() {

    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";

    $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

    $calendarios = Calendario_::calendarios();
    $sedes = Sede_::sedes462();
    $modalidades = Modalidad_::modalidades();
    $planificaciones = Planificacion_::planificaciones();

    $comision = (empty($persona_id)) ? new Comision_(): DbMy::getInstance()->CreateDataProvider()->fetchEntityById("comision", $persona_id);
    include plugin_dir_path(__FILE__) . 'ac2_comision_form.html';

    if(!empty($comision))
        ac2_init_cursos($comision->id);
}

function ac2_init_cursos($comision_id) {
    $disposiciones = Disposicion_::disposicionesActuales();
    $dataProvider = DbMy::getInstance()->CreateDataProvider();

    $cursos = $dataProvider->fetchEntitiesByParams("curso", ["comision"=>$comision_id]);
    if($cursos)
        include plugin_dir_path(__FILE__) . 'ac2_curso_table_form.html';
    else 
        echo "<p>No hay cursos para mostrar de la comisión</p>";
}

//include plugin_dir_path(__FILE__) . 'ac2_comision_form_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_modify_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_add_handle.php';



