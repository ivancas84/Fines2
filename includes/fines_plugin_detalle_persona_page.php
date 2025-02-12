<?php


function fines_plugin_detalle_persona_page() {
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


    include plugin_dir_path(__FILE__) . '../html/fines_plugin_detalle_persona_page_form_persona.php';



  if ($alumno){ 
    include plugin_dir_path(__FILE__) . '../html/fines_plugin_detalle_persona_page_form_alumno.php';

  }
 else { 
       echo "<p>No se encontró un alumno asociado con esta persona.</p>";
 }

    if ($alumno) {
        $calificaciones = $wpdb->get_results(
            $wpdb->prepare("
                SELECT calificacion.nota_final, calificacion.crec, planificacion.anio, planificacion.semestre, plan.orientacion, asignatura.nombre FROM calificacion 
                INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
                INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
                INNER JOIN plan ON (planificacion.plan = plan.id)
                INNER JOIN asignatura ON (disposicion.asignatura = asignatura.id)
                WHERE alumno = '$alumno->id'
                ORDER BY planificacion.anio, planificacion.semestre
				LIMIT 100;
            ")
        );

        if ($calificaciones) {
            echo "<h2>Calificaciones</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Asignatura</th>
                    <th>Año</th>
                    <th>Semestre</th>
                    <th>Nota Final</th>
                    <th>CREC</th>
                  </tr>";

            foreach ($calificaciones as $cal) {
                echo "<tr>
                        <td>{$cal->nombre}</td>
                        <td>{$cal->anio}</td>
                        <td>{$cal->semestre}</td>
                        <td>{$cal->nota_final}</td>
                        <td>{$cal->crec}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
    } else {
        echo "<p>No se encontró un alumno asociado con esta persona.</p>";
    }
	
}

