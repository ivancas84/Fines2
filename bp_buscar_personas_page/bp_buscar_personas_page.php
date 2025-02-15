<?php

function bp_buscar_personas_page() {
    $wpdb = fines_plugin_db_connection();

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
                WHERE apellidos LIKE '%$search%' 
                OR nombres LIKE '%$search%'
                OR numero_documento LIKE '%$search%'
                OR telefono LIKE '%$search%'
                OR email LIKE '%$search%'
                OR email_abc LIKE '%$search%'";

            // Append order by clause
            $sql .= " ORDER BY " . esc_sql($selected_order);

            // Execute query
            $personas  = $wpdb->get_results($wpdb->prepare($sql));

            if ($personas ) {
                include plugin_dir_path(__FILE__) . 'bp_tabla_personas.html';
            } else {
                echo "<p>No se encontraron personas.</p>";
            }
    }

    echo "</div>";
}