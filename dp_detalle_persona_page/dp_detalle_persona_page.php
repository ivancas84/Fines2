<?php

include_once plugin_dir_path(__FILE__) . 'dp_detalle_persona.php';


function dp_detalle_persona_page() {
    if (!isset($_GET['persona_id']) || empty($_GET['persona_id'])) {
        echo "<p>Error: No se especificó el ID de la persona.</p>";
        return;
    }

    $persona_id = $_GET['persona_id'];
    $wpdb = fines_plugin_db_connect();

    dp_detalle_persona($persona_id, $wpdb);

    echo '<a href="javascript:history.back();" class="button">Volver</a>';
}
