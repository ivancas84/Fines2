<?php


function rd_rindex_division_page() {
    
    if (!isset($_GET['comision_pfid']) || empty($_GET['comision_pfid'])) {
        echo "<p>Error: No se especificó el pfid.</p>";
        return;
    }

    $pfid = $_GET['comision_pfid'];

    $wpdb = fines_plugin_db_connection();

    // Step 1: Get the dynamic headers (detalle_asignatura)
    $query_headers = "
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura
        FROM disposicion
        INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
        INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
        WHERE planificacion.plan IN (
            SELECT planificacion.plan
            FROM comision
            INNER JOIN planificacion ON comision.planificacion = planificacion.id
            WHERE comision.pfid = %s
        )
    ";
    $headers = $wpdb->get_col($wpdb->prepare($query_headers, $pfid));

    // Step 2: Get student data and grades
    $query_students = "
        SELECT 
            DISTINCT persona.id AS persona_id, 
            persona.nombres, 
            persona.apellidos, 
            persona.numero_documento, 
            calificacion_aprobada.detalle_asignatura, 
            calificacion_aprobada.max_nota_final, 
            calificacion_aprobada.max_crec
        FROM alumno
        INNER JOIN persona ON alumno.persona = persona.id
        INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
        LEFT JOIN (
            SELECT 
                DISTINCT calificacion.alumno, 
                CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
                MAX(calificacion.nota_final) AS max_nota_final, 
                MAX(calificacion.crec) AS max_crec
            FROM calificacion
            INNER JOIN disposicion ON calificacion.disposicion = disposicion.id
            INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
            INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
            WHERE (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
            GROUP BY calificacion.alumno, detalle_asignatura
        ) AS calificacion_aprobada 
        ON calificacion_aprobada.alumno = alumno.id 
        WHERE alumno.id IN (
            SELECT DISTINCT alumno 
            FROM alumno_comision 
            INNER JOIN comision ON comision.id = alumno_comision.comision
            WHERE comision.pfid = %s
        )
    ";
    $students = $wpdb->get_results($wpdb->prepare($query_students, $pfid));

    // Step 3: Build the table
    $output = '<table border="1" cellpadding="5" cellspacing="0">';
    $output .= '<thead><tr>';
    $output .= '<th>Datos Alumno</th>';
    foreach ($headers as $header) {
        $output .= '<th>' . esc_html($header) . '</th>';
    }
    $output .= '</tr></thead>';

    // Step 4: Populate the table
$output .= '<tbody>';
$student_data = [];
foreach ($students as $student) {
    $student_link = admin_url('admin.php?page=fines-plugin-detalle-persona&persona_id=' . $student->persona_id);
    $key = '<a href="' . esc_url($student_link) . '">' . esc_html($student->nombres . ' ' . $student->apellidos . ' (' . $student->numero_documento . ')') . '</a>';

    if (!isset($student_data[$key])) {
        $student_data[$key] = array_fill_keys($headers, '');
    }
    $value = '';
    if ($student->max_nota_final >= 7) {
        $value = $student->max_nota_final;
    } elseif ($student->max_crec >= 4) {
        $value = $student->max_crec;
    }
    $student_data[$key][$student->detalle_asignatura] = $value;
}

foreach ($student_data as $student_name => $grades) {
    $output .= '<tr>';
    $output .= '<td>' . $student_name . '</td>'; // Now includes a clickable link
    foreach ($headers as $header) {
        $output .= '<td>' . esc_html($grades[$header]) . '</td>';
    }
    $output .= '</tr>';
}
    $output .= '</tbody></table>';
    echo $output;
}

