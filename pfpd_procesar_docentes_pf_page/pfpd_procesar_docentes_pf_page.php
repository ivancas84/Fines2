<?php

function pfpd_procesar_docentes_pf_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }


    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'pfpd_form.html';


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