<?php

/**
 * Generar comisiones del semestre siguiente
 */
function fines_plugin_comisiones_siguientes_semestre() {
    $wpdb = fines_plugin_db_connection();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? intval($_GET['calendario']) : '';
	$selected_calendario_siguiente = isset($_GET['calendario_siguiente']) ? intval($_GET['calendario_siguiente']) : '';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . '../html/fines_plugin_comisiones_siguientes_semestre_formulario.php';

    if (isset($_GET['submit']) && !empty($_GET['calendario']) && !empty($_GET['calendario_siguiente'])) {
          
        
        $calendario_id = intval($_GET['calendario']);
            
             // Build SQL query
            $sql = sqlSelectComision_autorizada__By_calendario__Without_tramo32_and_siguiente($calendario_id);
               
            // Execute query
            $comisiones = $wpdb->get_results($wpdb->prepare($sql));
	

			foreach($comisiones as $comision){
				
			}
			
            if ($comisiones) {
                include plugin_dir_path(__FILE__) . '../html/fines_plugin_comisiones_page_tabla.php';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    } else {
		echo "<p>Complete el formulario.</p>";
	}

    echo "</div>";
}