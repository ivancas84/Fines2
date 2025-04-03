<?php

function lcu_lista_cursos_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';
    $selected_order = isset($_GET['order_by']) ? sanitize_text_field($_GET['order_by']) : 'tramo';

    if(!$selected_calendario){
        $selected_calendario = $calendarios[0]->id;
    } 

    $calendario_id = $selected_calendario;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lcu_formulario_busqueda_html.php';

    switch ($selected_order){
        case "tramo":
            $order_by = "ORDER BY tramo ASC";
            break;

        default:
            $order_by = "";
    }
    
    $cursos = wpdbCursosConTomasActivas($wpdb, $calendario_id, $order_by); 

    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'lcu_tabla_cursos.html';
    } else {
        echo "<p>No se encontraron cursos para este calendario.</p>";
    }

    echo "</div>";
}