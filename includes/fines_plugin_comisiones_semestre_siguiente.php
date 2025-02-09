<?php

/**
 * Generar comisiones del semestre siguiente
 */
function fines_plugin_comisiones_semestre_siguiente() {
    $wpdb = fines_plugin_db_connection();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? intval($_GET['calendario']) : '';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . '../html/fines_plugin_comisiones_semestre_siguiente_formulario.php';

    die();
    if (isset($_GET['submit']) && !empty($_GET['calendario']) && !empty($_GET['calendario_siguiente'])) {
          
        
        $calendario_id = intval($_GET['calendario']);
            
             // Build SQL query
            $sql = sqlSelectComision_autorizada__By_calendario__Without_tramo32() . "
                WHERE calendario = '$calendario_id'";

            $sql .= " AND comision.autorizada = true";
            $sql .= " GROUP BY comision.id ";
            
            // Execute query
            $comisiones = $wpdb->get_results($wpdb->prepare($sql));

            if ($comisiones) {
                include plugin_dir_path(__FILE__) . '../html/fines_plugin_comisiones_page_tabla.php';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    }

    echo "</div>";
}