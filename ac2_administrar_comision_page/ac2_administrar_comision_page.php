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
  
    function ac2_administrar_comision_page() {

            wp_page_message();
            $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

            $calendarios = Calendario_::calendarios();
            $sedes = Sede_::sedes462();
            $modalidades = Modalidad_::modalidades();
            $planificaciones = Planificacion_::planificaciones();

            $comision = (empty($comision_id)) ? new Comision_(): DbMy::getInstance()->CreateDataProvider()->fetchEntityById("comision", $comision_id);
            include plugin_dir_path(__FILE__) . 'ac2_comision_form.html';

        if(!empty($comision))
            ac2_init_cursos($comision);
    }

    function ac2_init_cursos(Comision_ $comision) {
        $disposiciones = Disposicion_::disposicionesActuales();

        $dataProvider = DbMy::getInstance()->CreateDataProvider();

        $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision"=>$comision->id]);
        if($cursos)
            include plugin_dir_path(__FILE__) . 'ac2_curso_table_form.html';
        else 
            echo "<p>No hay cursos para mostrar de la comisión</p>";
    }

//include plugin_dir_path(__FILE__) . 'ac2_comision_form_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_modify_handle.php';

//include plugin_dir_path(__FILE__) . 'ac2_curso_add_handle.php';