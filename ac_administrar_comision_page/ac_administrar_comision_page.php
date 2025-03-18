<?php


function ac_administrar_comision_page() {
    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";

    $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;
    $wpdb = fines_plugin_db_connect();

    $calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
    $sedes = $wpdb->get_results("SELECT * FROM sede WHERE centro_educativo = '6047d36d50316'  ORDER BY nombre ASC");
    $modalidades = $wpdb->get_results("SELECT * FROM modalidad ORDER BY nombre ASC");
    $planificaciones = $wpdb->get_results("
        SELECT planificacion.id, 
        CONCAT(plan.orientacion, ' ', plan.resolucion, ' ', planificacion.anio, '/', planificacion.semestre) as label 
        FROM planificacion 
        INNER JOIN plan ON planificacion.plan = plan.id 
        ORDER BY plan.resolucion, plan.orientacion, planificacion.anio, planificacion.semestre
    ");
    
    $selected_calendario = isset($_GET['calendario']) ? $_GET['calendario'] : '';
    $selected_sede = isset($_GET['sede']) ? $_GET['sede'] : '';
    $selected_modalidad = isset($_GET['modalidad']) ? $_GET['modalidad'] : '';
    $autorizada = false;
    $apertura = false;
    $publicada = false;
    $pfid = '';
    $observaciones = '';
    $division = '';
    $selected_planificacion = isset($_GET['planificacion']) ? $_GET['planificacion'] : '';
    $selected_turno = "Verspertino";

    if(isset($comision_id)){

        $comision = $wpdb->get_row(
            $wpdb->prepare("SELECT *
                            FROM comision                        
                            WHERE comision.id = '{$comision_id}'")
        );

        if($comision) {
            $selected_calendario = $comision->calendario;
            $selected_sede = $comision->sede;
            $selected_modalidad = $comision->modalidad;
            $autorizada = $comision->autorizada;
            $apertura = $comision->apertura;
            $publicada = $comision->publicada;
            $selected_planificacion = $comision->planificacion;
            $selected_turno = $comision->turno;
            $pfid = $comision->pfid;
            $observaciones = $comision->observaciones;
            $division = $comision->division;

            include plugin_dir_path(__FILE__) . 'ac_comision_form.html';

            ac_init_cursos($wpdb, $comision->id);
        }
        
    } else {
        include plugin_dir_path(__FILE__) . 'ac_comision_form.html';
    } 


}

function ac_init_cursos($wpdb, $comision_id) {
    $cursos = wpdbCursos__By_comision($wpdb, $comision_id);
    print_r($cursos);
    if($cursos)
        include plugin_dir_path(__FILE__) . 'ac_curso_table_form.html';
    else 
        echo "<p>No hay cursos para mostrar de la comisión</p>";
}

include plugin_dir_path(__FILE__) . 'ac_comision_form_handle.php';

