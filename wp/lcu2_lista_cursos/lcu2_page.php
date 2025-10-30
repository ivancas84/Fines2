<?php

//require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/fines-config.php');


use \SqlOrganize\Sql\DbMy;
use \Fines2\CursoDAO;
use \Fines2\TomaDAO;
use \Fines2\CalificacionDAO;
use SqlOrganize\Utils\ValueTypesUtils;




add_submenu_page(
    'fines-plugin', 
    'Lista de Cursos',
    'Lista de Cursos', 
    'edit_posts', 
    'fines-plugin-lcu2', 
    'lcu2_lista_cursos_page'
  );

function lcu2_lista_cursos_page() {

    $db = \App\Context::getFinesDb();
    $dataProvider = $db->CreateDataProvider();

    $calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';

    if(!$selected_calendario){
        $selected_calendario = $calendarios[0]->id;
    } 

    $calendario_id = $selected_calendario;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lcu2_form_html.php';
 
    $cursos = CursoDAO::CursosActivosConTomasActivasByCalendario($calendario_id);

    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'lcu2_tabla_cursos_html.php';
    } else {
        echo "<p>No se encontraron cursos para este calendario.</p>";
    }

    echo "</div>";
}