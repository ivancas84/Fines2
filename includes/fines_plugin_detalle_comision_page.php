<?php


function fines_plugin_detalle_comision_page() {
    $comision_id = $_GET['comision_id'];
    $wpdb = fines_plugin_db_connection();


    
    $calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? $_GET['calendario'] : '';
    $sedes = $wpdb->get_results("SELECT * FROM sede WHERE centro_educativo = '6047d36d50316'  ORDER BY nombre ASC");
	$selected_sede = isset($_GET['sede']) ? $_GET['calendario'] : '';

    if(isset($comision_id)){

        $comision = $wpdb->get_row(
            $wpdb->prepare("SELECT *
                            FROM comision                        
                            WHERE comision.id = '{$comision_id}'")
        );
    }


    include plugin_dir_path(__FILE__) . '../html/fines_plugin_detalle_comision_page_form_comision.php';


	
}

