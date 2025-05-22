<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');


add_submenu_page(
    'fines-plugin', 
    'Lista de Cursos',
    'Lista de Cursos', 
    'edit_posts', 
    'fines-plugin-lista-cursos', 
    'lcu_lista_cursos_page'
  );

function lcu_lista_cursos_page() {
    $pdo = new PdoFines();

	$calendarios = $pdo->calendarios();
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
    
    $cursos = $pdo->cursosConTomasActivas($calendario_id, $order_by);

    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'lcu_tabla_cursos.html';
    } else {
        echo "<p>No se encontraron cursos para este calendario.</p>";
    }

    echo "</div>";
}