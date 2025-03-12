<?php

function atc_administrar_toma_comision_page() {
    $wpdb = fines_plugin_db_connect();
    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message){
        echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
    }

    atc_comision_detail($wpdb);
    //as_init_domicilio($wpdb, $sede);
    //as_init_designaciones($wpdb, $sede);
}

function atc_comision_detail($wpdb) {
    $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;

    $comision = wpdbComision__By_id($wpdb, $comision_id);

    if ($comision) {
        include plugin_dir_path(__FILE__) . 'atc_comision_detail.html';

        atc_init_tomas($wpdb, $comision_id);
    } else {
        echo "<p>No se encontraron resultados para la comisión ID: " . esc_html($comision_id) . "</p>";
    }
}


function atc_init_tomas($wpdb, $comision_id) {
        //$cargos = $wpdb->get_results("SELECT * FROM cargo ORDER BY descripcion ASC");
        

    $tomas = wpdbTomas__By_comision($wpdb, $comision_id);

    $estados = wpdbEstadosToma($wpdb);
    $estados_contralor = wpdbEstadosContralorToma($wpdb);
    $movimientos = wpdbMovimientosToma($wpdb);


    // Obtener parámetros GET
    $selected_calendario = isset($_GET['calendario']) ? intval($_GET['calendario']) : '';

    include plugin_dir_path(__FILE__) . 'atc_tomas_table_form.html';
}

include plugin_dir_path(__FILE__) . 'atc_toma_modify_handle.php';

?>