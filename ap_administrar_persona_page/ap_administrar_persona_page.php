<?php

function ap_administrar_persona_page() {
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;

    $wpdb = fines_plugin_db_connect();

    // Fetch student details from the database
    $persona = wpdbPersona__By_id($wpdb, $persona_id);

    include plugin_dir_path(__FILE__) . 'ap_persona_form.html';

    if($persona){


        $planes = $wpdb->get_results("SELECT * FROM plan");
        $selected_plan = null;

        $resoluciones = $wpdb->get_results("SELECT * FROM plan ORDER BY resolucion");
        $selected_resolucion = null;

        $estados = $wpdb->get_col("SELECT DISTINCT estado_inscripcion FROM alumno ORDER BY estado_inscripcion");
        $selected_estado = null;

        $alumno = wpdbAlumno__By_idPersona($wpdb, $persona_id);
        
        if ($alumno){
            $alumno_id = $alumno->id;
            $selected_plan = $alumno->plan;
            $selected_resolucion = $alumno->resolucion_inscripcion;
            $selected_estado = $alumno->estado_inscripcion;
            $comisiones = wpdbComisiones__By_idAlumno($wpdb, $alumno->id);
            $calificaciones = wpdbCalificaciones__By_idAlumno($wpdb, $alumno->id);
        }

        else {
            echo "<p>No se encontró un alumno asociado con esta persona.</p>";
            $alumno_id = null;
            $comisiones = null;
            $calificaciones = null;
        }

        include plugin_dir_path(__FILE__) . 'ap_alumno_form.html';
    
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

        $detalles = wpdbDetalles__By_idPersona($wpdb, $persona_id);

        if ($detalles) {
            include plugin_dir_path(__FILE__) . '../dp_detalle_persona_page/dp_detalles_table.html';
        } else {
            echo "<p>No se encontraron detalles para esta persona.</p>";
        }

    } else {
        echo "<p>No se encontró una persona asociada a ese id.</p>";
    }
    echo '<a href="javascript:history.back();" class="button">Volver</a>';
}

include plugin_dir_path(__FILE__) . 'ap_persona_form_handle.php';

include plugin_dir_path(__FILE__) . 'ap_alumno_form_handle.php';
