<?php

function lc_lista_comisiones_page() {
    $wpdb = fines_plugin_db_connection();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? intval($_GET['calendario']) : '';
    $selected_order = isset($_GET['order_by']) ? sanitize_text_field($_GET['order_by']) : 'tramo';
    $filter_autorizada = isset($_GET['autorizada']) ? true : false;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lc_formulario_busqueda_html.php';

    if (isset($_GET['submit']) && !empty($_GET['calendario'])) {
            $calendario_id = intval($_GET['calendario']);
            
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
                include plugin_dir_path(__FILE__) . 'lc_tabla_comisiones_html.php';
            } else {
                echo "<p>No se encontraron comisiones para este calendario.</p>";
            }
    }

    echo "</div>";
}