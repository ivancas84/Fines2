<?php

function ls_lista_sedes_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';
    $selected_order = isset($_GET['order_by']) ? sanitize_text_field($_GET['order_by']) : 'nombre';


    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'ls_formulario_busqueda.html';

    if (isset($_GET['submit'])) {
            $calendario_id = sanitize_or_null_text_field($_GET['calendario']);
            
            if($calendario_id){
               $sede_ids = wpdbIdSedes__By_calendario($wpdb, $calendario_id); 
            } else {
                $sede_ids = null;
            }

            if($sede_ids){
                $sedes = wpdbSedes__By_ids($wpdb, $sede_ids, $selected_order);
            } else {
                $sedes = wpdbSedes($wpdb, $selected_order);
            }

            if ($sedes) {
                include plugin_dir_path(__FILE__) . 'ls_tabla_sedes.html';
            } else {
                echo "<p>No se encontraron sedes.</p>";
            }
    }

    echo "</div>";
}