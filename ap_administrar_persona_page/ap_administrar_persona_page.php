<?php

function ap_administrar_persona_page() {
    if (!isset($_GET['persona_id']) || empty($_GET['persona_id'])) {
        echo "<p>Error: No se especificó el ID de la persona.</p>";
        return;
    }

    $persona_id = $_GET['persona_id'];
    $wpdb = fines_plugin_db_connect();

    // Fetch student details from the database
    $persona = wpdbPersona__By_id($wpdb, $persona_id);

    $alumno = wpdbAlumno__By_idPersona($wpdb, $persona_id);

include plugin_dir_path(__FILE__) . 'ap_detalle_persona.html';

if ($alumno){ 
    include plugin_dir_path(__FILE__) . 'ap_detalle_alumno.html';
}
else { 
    echo "<p>No se encontró un alumno asociado con esta persona.</p>";
}

    if ($alumno) {
        $comisiones = wpdbComisiones__By_idAlumno($wpdb, $alumno->id);

        $calificaciones = wpdbCalificaciones__By_idAlumno($wpdb, $alumno->id);

        if ($comisiones) {
            include plugin_dir_path(__FILE__) . 'ap_comisiones_table.html';
        } else {
            echo "<p>No se encontraron comisiones para este alumno.</p>";
        }

        if ($calificaciones) {
            include plugin_dir_path(__FILE__) . 'ap_calificaciones_table.html';

        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }

    } else {
        echo "<p>No se encontró un alumno asociado con esta persona.</p>";
    }

    echo '<a href="javascript:history.back();" class="button">Volver</a>';
}
