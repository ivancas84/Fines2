<?php

require_once __DIR__ . '/db-config.php';

use \Fines2\Comision_;
use \Fines2\CalendarioDAO;
use \Fines2\SedeDAO;
use \Fines2\ModalidadDAO;
use \Fines2\PlanificacionDAO;
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
    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";

    $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

    $calendarios = CalendarioDAO::calendarios();
    $sedes = SedeDAO::sedes462();
    $modalidades = ModalidadDAO::modalidades();
    $planificaciones = PlanificacionDAO::planificaciones();
    

    if(isset($comision_id)){
        $dataProvider = DbMy::getInstance()->CreateDataProvider();

        $comision = $dataProvider->fetchEntityById("comision", $comision_id);
        include plugin_dir_path(__FILE__) . 'ac_comision_form.html';

        ac_init_cursos($wpdb, $comision->id);
        
    } else {
        $comision = new Comision_();
        include plugin_dir_path(__FILE__) . 'ac_comision_form.html';
    } 
}

function ac_init_cursos($wpdb, $comision_id) {
    $disposiciones = wpdbDisposiciones($wpdb);
    $cursos = wpdbCursos__By_comision($wpdb, $comision_id);
    if($cursos)
        include plugin_dir_path(__FILE__) . 'ac_curso_table_form.html';
    else 
        echo "<p>No hay cursos para mostrar de la comisión</p>";
}

include plugin_dir_path(__FILE__) . 'ac_comision_form_handle.php';

include plugin_dir_path(__FILE__) . 'ac_curso_modify_handle.php';

include plugin_dir_path(__FILE__) . 'ac_curso_add_handle.php';



