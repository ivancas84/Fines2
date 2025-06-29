<?php

function bp_buscar_personas_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $selected_order = isset($_GET['order_by']) ? sanitize_text_field($_GET['order_by']) : 'apellidos';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'bp_formulario_busqueda.html';

    if (isset($_GET['submit']) && !empty($_GET['search'])) {
            $sql = "
                    SELECT * FROM persona 
                    WHERE lower(apellidos)  LIKE lower(%s) 
                    OR lower(nombres) LIKE lower(%s)
                    OR lower(numero_documento) LIKE lower(%s)
                    OR lower(telefono) LIKE lower(%s)
                    OR lower(email) LIKE lower(%s)
                    OR lower(email_abc) LIKE lower(%s)";

            // Append order by clause
            $sql .= " ORDER BY " . esc_sql($selected_order);

            // Execute query
            $personas  = $wpdb->get_results($wpdb->prepare($sql, "%".$search."%", "%".$search."%", "%".$search."%", "%".$search."%", "%".$search."%", "%".$search."%"));

            if ($personas ) {
                include plugin_dir_path(__FILE__) . 'bp_tabla_personas.html';
            } else {
                echo "<p>No se encontraron personas.</p>";
            }
    }

    echo "</div>";
}