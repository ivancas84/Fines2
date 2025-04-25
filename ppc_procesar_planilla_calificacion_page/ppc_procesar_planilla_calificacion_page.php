<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');


function ppc_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();
	$comision_id = isset($_GET['comision_id']) ? sanitize_text_field($_GET['comision_id']) : '';
    $comision = $pdo->comisionById($comision_id);
    
    include plugin_dir_path(__FILE__) . 'ppc_form.html';

    if (isset($_POST['submit']) && !empty($_POST['data'])) {
        $rawData = trim($_POST['data']);
        $lines = explode(PHP_EOL, $rawData);
    
        // Clean up each line
        $rows = array_map(function($line) {
            return array_map('trim', explode("\t", $line));
        }, $lines);
    
        // Use first row as headers
        $headers = array_shift($rows);
    
        // Convert to array of associative arrays
        $result = array_map(function($row) use ($headers) {
            return array_combine($headers, $row);
        }, $rows);
    
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    echo "</div>";
}