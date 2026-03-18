<?php

use App\Context;
use Fines2\DataAccess\CursoDAO;
use Fines2\Model\Calendario_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;

add_submenu_page(
    FINES_PLUGIN, 
    'Cursos',
    'Cursos', 
    'edit_posts', 
    FINES_PLUGIN.'-lcu3', 
    'lcu3_page'
  );

function lcu3_page() {

    /** @var Db */ $db = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    /** @var Calendario_[] */ $calendarios = $dataProvider->fetchAllEntitiesByParams("calendario",[], ["anio" => "DESC", "semestre" => "DESC"]);
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';

    if(!$selected_calendario){
        $selected_calendario = $calendarios[0]->id;
    } 

    $calendario_id = $selected_calendario;

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'lcu3_form_html.php';
 
    $cursos = CursoDAO::CursosActivosConTomasActivasByCalendario($calendario_id);

    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'lcu3_tabla_cursos_html.php';
    } else {
        echo "<p>No se encontraron cursos para este calendario.</p>";
    }

    echo "</div>";
}