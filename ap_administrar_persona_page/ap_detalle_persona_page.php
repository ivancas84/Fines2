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
        $comisiones = $wpdb->get_results(
            $wpdb->prepare("
            SELECT 
            alumno_comision.estado, comision.pfid, sede.nombre AS detalle_sede, 
            CONCAT(sede.numero, ' ', comision.division) AS codigo_interno, CONCAT(planificacion.anio, planificacion.semestre) AS tramo, 
            CONCAT(calendario.anio,'-',calendario.semestre) AS periodo, 
            CONCAT(plan.orientacion, ' ', plan.resolucion) AS detalle_plan,
            modalidad.nombre AS detalle_modalidad
                    FROM alumno_comision
                    INNER JOIN comision ON (alumno_comision.comision = comision.id)
                    INNER JOIN sede ON (comision.sede = sede.id)
                    INNER JOIN planificacion ON (comision.planificacion = planificacion.id)
                    INNER JOIN plan ON (planificacion.plan = plan.id)
                    INNER JOIN calendario ON (comision.calendario = calendario.id)			
                    INNER JOIN modalidad ON (comision.modalidad = modalidad.id)			
                
            WHERE alumno = '$alumno->id'
            ORDER BY calendario.anio DESC, calendario.semestre DESC
            LIMIT 100
            ")
        );

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
                calendario.semestre,
                comision.pfid,
                CONCAT(calendario.anio, '-', calendario.semestre) AS periodo,
                CONCAT(toma_activa.nombres, ' ', toma_activa.apellidos, ' ', toma_activa.numero_documento) AS docente                    
                
            FROM calificacion 
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
            INNER JOIN plan ON (planificacion.plan = plan.id)
            INNER JOIN asignatura ON (disposicion.asignatura = asignatura.id)
            LEFT JOIN curso ON (calificacion.curso = curso.id)
            LEFT JOIN comision ON (curso.comision = comision.id)				
            LEFT JOIN calendario ON (comision.calendario = calendario.id)			
            LEFT JOIN (
                SELECT toma.curso, persona.nombres, persona.apellidos, persona.numero_documento 
                FROM toma 
                INNER JOIN persona ON (toma.docente = persona.id)
                WHERE estado = 'Aprobada' 
                AND estado_contralor != 'Modificar'
            ) AS toma_activa ON (toma_activa.curso = curso.id)
            WHERE alumno = '$alumno->id'
            ORDER BY planificacion.anio, planificacion.semestre
            LIMIT 100
            ")
        );

        if ($comisiones) {
            include plugin_dir_path(__FILE__) . 'dp_comisiones_table_html.php';
        } else {
            echo "<p>No se encontraron comisiones para este alumno.</p>";
        }

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