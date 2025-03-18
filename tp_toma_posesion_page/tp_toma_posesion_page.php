<?php

function tp_toma_posesion_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

    $calendario_id = "202502110007";

    echo "<div class=\"wrap\">";
            
    $cursos = wpdbCursos_autorizados_publicados__By_calendario($wpdb, $calendario_id);
             // Build SQL query
    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'tp_tabla.html';
    } else {
            echo "<p>No se encontraron cursos para tomar posesión.</p>";
    }

    echo "</div>";
}