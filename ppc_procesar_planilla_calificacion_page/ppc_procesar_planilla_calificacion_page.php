<?php

add_submenu_page(
    null, 
    'Procesar Planilla Calificación',
    'Procesar Planilla Calificación', 
    'edit_posts', 
    'fines-plugin-ppc', 
    'ppc_procesar_planilla_calificacion_page'
  );


require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

function ppc_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();
	$curso_id = isset($_GET['curso_id']) ? sanitize_text_field($_GET['curso_id']) : '';
    $curso = $pdo->cursoById($curso_id);
    
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc_form.html';
    }
    else  {

        $rawData = trim($_POST['data']);
        $format = $_POST['format'];
        $result = Tools::excelParse($rawData);

        foreach($result as $row) {
            $data = array();

            if($format == "pf"){
                foreach($row as $key => $value) {
                    if(str_contains($key, "Nombre")){
                        $data  = array_merge($data, Tools::parseFirstColumnCalificacionPF($value));
                    } else if(str_contains($key, "Final")){
                        $data["calificacion"] = $value;
                    }
                } 
                
                print_r($data);
                echo "<br>";  
            }

        }

    }
}