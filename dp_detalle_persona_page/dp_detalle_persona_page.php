<?php


function dp_detalle_persona_page() {
    if (!isset($_GET['persona_id']) || empty($_GET['persona_id'])) {
        echo "<p>Error: No se especificó el ID de la persona.</p>";
        return;
    }

    $persona_id = $_GET['persona_id'];
    $wpdb = fines_plugin_db_connection();

    // Fetch student details from the database
    $persona = $wpdb->get_row(
        $wpdb->prepare("SELECT persona.nombres, persona.apellidos, persona.numero_documento, persona.telefono
                        FROM persona                        
                        WHERE persona.id = '{$persona_id}'")
    );

    $alumno = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM alumno WHERE persona = '$persona_id'")
    );


    include plugin_dir_path(__FILE__) . 'dp_persona_form_html.php';



  if ($alumno){ 
    include plugin_dir_path(__FILE__) . 'dp_alumno_form_html.php';

  }
 else { 
       echo "<p>No se encontró un alumno asociado con esta persona.</p>";
 }

    if ($alumno) {
        $calificaciones = $wpdb->get_results(
            $wpdb->prepare("
                SELECT 
                    calificacion.nota_final, 
                    calificacion.crec, 
                    planificacion.anio, 
                    planificacion.semestre, 
                    plan.orientacion, 
                    asignatura.nombre,
                    calendario.anio,
                    calendario.semestre 
                FROM calificacion 
                INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
                INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
                INNER JOIN plan ON (planificacion.plan = plan.id)
                INNER JOIN asignatura ON (disposicion.asignatura = asignatura.id)
                LEFT JOIN curso ON (calificacion.curso = curso.id)
                LEFT JOIN comision ON (curso.comision = comision.id)
                LEFT JOIN calendario ON (comision.calendario = calendario.id)
                WHERE alumno = '$alumno->id'
                ORDER BY planificacion.anio, planificacion.semestre
				LIMIT 100;
            ")
        );

        if ($calificaciones) {
            include plugin_dir_path(__FILE__) . 'dp_calificaciones_table_html.php';

        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
    } else {
        echo "<p>No se encontró un alumno asociado con esta persona.</p>";
    }
	
}

include plugin_dir_path(__FILE__) . 'dp_persona_form_handle.php';
include plugin_dir_path(__FILE__) . 'dp_alumno_form_handle.php';