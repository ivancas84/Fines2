<?php

function lc_lista_comisiones_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'taa_seleccionar_calendario.html';

    if (isset($_GET['submit']) && !empty($_GET['calendario'])) {
            $calendario_id = sanitize_text_field($_GET['calendario']);
            
            $comisiones = wpdbComisiones_autorizadas__By_calendario__Without_tramo32($wpdb, $calendario_id);
            
             // Build SQL query
            $sql = sqlSelectComision() . "
                WHERE calendario = '$calendario_id'";

            // Add 'autorizada' filter if checkbox is checked
            if ($filter_autorizada) {
                $sql .= " AND comision.autorizada = true";
            }

            $sql .= " GROUP BY comision.id ";

            // Append order by clause
            $sql .= " ORDER BY " . esc_sql($selected_order);

            // Execute query
            $comisiones = $wpdb->get_results($wpdb->prepare($sql));

            if ($comisiones) {
                include plugin_dir_path(__FILE__) . 'lc_tabla_comisiones.html';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    }

    echo "</div>";
}