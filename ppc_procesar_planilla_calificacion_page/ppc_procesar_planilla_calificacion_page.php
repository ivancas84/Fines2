<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');


function ppc_procesar_planilla_calificacion_page() {

    $pdo = new PdoFines();


    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'ppc_form.html';


    if (isset($_POST['submit']) && !empty($_POST['data'])) {
        $dataJson = stripslashes($_POST['data']); // Remove extra escaping
        $dataJson = json_decode($dataJson, true); // Decode once more if needed

        if ($dataJson && is_string($dataJson)) {
            $dataJson = json_decode($dataJson, true);
        }

        if ($data === null) {
            echo "JSON Decode Error: " . json_last_error_msg();
        } else {
            print_r($data);
        }
    }
    echo "</div>";
}