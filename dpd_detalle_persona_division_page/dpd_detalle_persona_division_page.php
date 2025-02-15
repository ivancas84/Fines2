<?php

include_once plugin_dir_path(__FILE__) . '../dp_detalle_persona_page/dp_detalle_persona.php';

function dpd_detalle_persona_division_page() {
    
    if (!isset($_GET['comision_pfid']) || empty($_GET['comision_pfid'])) {
        echo "<p>Error: No se especificó el pfid.</p>";
        return;
    }

    $pfid = $_GET['comision_pfid'];

    $wpdb = fines_plugin_db_connection();

    // Step 2: Get student data and grades
    $sql = "
       SELECT DISTINCT persona.id 
       FROM alumno 
       INNER JOIN persona ON alumno.persona = persona.id 
       INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno 
       WHERE alumno.id IN ( 
            SELECT DISTINCT alumno 
            FROM alumno_comision 
            INNER JOIN comision ON comision.id = alumno_comision.comision 
            WHERE comision.pfid = %s)
        ORDER BY persona.apellidos ASC, persona.nombres ASC
    ";
    $students = $wpdb->get_results($wpdb->prepare($sql, $pfid));

    for($i = 0 ; $i < count($students); $i++){
        echo "<hr>";
        echo "<strong>Alumno " . ($i + 1) . " de " . count($students) . "</strong>";
        dp_detalle_persona($students[$i]->id, $wpdb);
    }
}
?>