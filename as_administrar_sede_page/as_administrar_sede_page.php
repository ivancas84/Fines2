<?php

function as_administrar_sede_page() {
    $wpdb = fines_plugin_db_connect();
    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message){
        echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
    }

    $sede = as_init_sede($wpdb);
    as_init_domicilio($wpdb, $sede);
}

function as_init_sede($wpdb) {
    $sede_id = isset($_GET['sede_id']) ? $_GET['sede_id'] : null;

    $centros = $wpdb->get_results("SELECT * FROM centro_educativo ORDER BY nombre ASC");
    $centro_educativo = isset($_GET['centro_educativo']) ? $_GET['centro_educativo'] : fines_plugin_consts()["centro_educativo"];
    $numero = '';
    $nombre = '';
    $observaciones = '';
    $pfid = '';
    $pfid_organizacion = ''; 
    $domicilio_id = '';

    $sede = null;

    if(!empty($sede_id)){

        $sede = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM sede WHERE id = %s", $sede_id)
        );

        if($sede) {
            $centro_educativo = $sede->centro_educativo;
            $numero = $sede->numero;
            $nombre = $sede->nombre;
            $observaciones = $sede->observaciones;
            $pfid = $sede->pfid;
            $pfid_organizacion = $sede->pfid_organizacion;
            $domicilio_id = $sede->domicilio;
        }
    } 

    include plugin_dir_path(__FILE__) . 'as_sede_form.html';

    return $sede;
}

function as_init_domicilio($wpdb, $sede){
    if($sede){
        $domicilio_id = $sede->domicilio ? $sede->domicilio : '';
        $sede_id = $sede->id;
        $calle = '';
        $entre = '';
        $numero = '';
        $barrio = '';
        $localidad = '';
    
        if(!empty($domicilio_id)){
            $domicilio = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM domicilio WHERE id = %s", $sede->domicilio)
            );

            print_r($domicilio);
            if($domicilio){
                $domicilio_id = $domicilio->id;
                $calle = $domicilio->calle;
                $entre = $domicilio->entre;
                $numero = $domicilio->numero;
                $barrio = $domicilio->barrio;
                $localidad = $domicilio->localidad;
            }
        }

        include plugin_dir_path(__FILE__) . 'as_domicilio_form.html';
    }
}

include plugin_dir_path(__FILE__) . 'as_sede_form_handle.php';
include plugin_dir_path(__FILE__) . 'as_domicilio_form_handle.php';

?>