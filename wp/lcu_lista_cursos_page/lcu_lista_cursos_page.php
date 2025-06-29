<?php

//require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/db-config.php');


use \SqlOrganize\Sql\DbMy;
use \Fines2\CursoDAO;
use \Fines2\TomaDAO;



add_submenu_page(
    'fines-plugin', 
    'Lista de Cursos',
    'Lista de Cursos', 
    'edit_posts', 
    'fines-plugin-lista-cursos', 
    'lcu_lista_cursos_page'
  );

function lcu_lista_cursos_page() {

    $db = DbMy::getInstance();
    $dataProvider = $db->CreateDataProvider();

    $pdo = new PdoFines();

    $calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';

    if(!$selected_calendario){
        $selected_calendario = $calendarios[0]->id;
    } 

    $calendario_id = $selected_calendario;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lcu_formulario_busqueda_html.php';
 
    $cursos = CursoDAO::CursosActivosConTomasActivasByCalendario($calendario_id);

    foreach($cursos as $curso){

    }
    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'lcu_tabla_cursos.html';
    } else {
        echo "<p>No se encontraron cursos para este calendario.</p>";
    }

    echo "</div>";
}