<?php
function fines_plugin_lista_alumnos_page() {
	$wpdb = fines_plugin_db_connect();

    if (!isset($_GET['comision_id']) || empty($_GET['comision_id'])) {
        echo "<p>Error: No se especificó la comisión.</p>";
        return;
    }

    $comision_id = $_GET['comision_id'];

    // Fetch commission details
	$comision = $wpdb->get_row(
		$wpdb->prepare(sqlSelectComision() . " WHERE comision.id = '$comision_id'")
	);

 
    // Query to fetch students associated with the given commission
    $alumnos = $wpdb->get_results(
        $wpdb->prepare("
            SELECT alumno.id, persona.id AS persona_id, persona.nombres, persona.apellidos, persona.numero_documento, 
			calificacion_aprobada.tramo, calificacion_aprobada.cantidad_aprobadas
			FROM alumno
            INNER JOIN persona ON (alumno.persona = persona.id)
			INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
			LEFT JOIN (
					SELECT calificacion.alumno, planificacion.plan,
                	CONCAT(planificacion.anio, '°', planificacion.semestre, 'C') AS tramo, COUNT(*) as cantidad_aprobadas 
					FROM calificacion
					INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
					INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)					
					WHERE (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                	GROUP BY calificacion.alumno, planificacion.plan, tramo
			) AS calificacion_aprobada ON calificacion_aprobada.alumno = alumno.id AND calificacion_aprobada.plan = alumno.plan
            WHERE alumno_comision.comision = '$comision_id' LIMIT 100"
			)
    );

    // Define all possible "tramo" values
    $tramo_values = ["1°1C", "1°2C", "2°1C", "2°2C", "3°1C", "3°2C"];

    // Organize students' data
    $student_data = [];
    foreach ($alumnos as $alumno) {
        $student_id = $alumno->id;

        if (!isset($student_data[$student_id])) {
            $student_data[$student_id] = [
                'persona_id' => $alumno->persona_id,
                'nombres' => $alumno->nombres,
                'apellidos' => $alumno->apellidos,
                'numero_documento' => $alumno->numero_documento,
                'tramos' => array_fill_keys($tramo_values, '0') // Default values for all tramos
            ];
        }

        if ($alumno->tramo && in_array($alumno->tramo, $tramo_values)) {
            $student_data[$student_id]['tramos'][$alumno->tramo] = $alumno->cantidad_aprobadas;
        }
    }

    // Display the commission information
    echo "<div class='wrap'>";
    echo "<h1>Lista de Alumnos - Comisión {$comision->pfid}</h1>";
    echo "<h3>{$comision->nombre} {$comision->tramo} {$comision->orientacion}</h3>";

    if (!empty($student_data)) {
        echo "<table class='wp-list-table widefat striped'>";
        echo "<thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>1°1C</th>
                    <th>1°2C</th>
                    <th>2°1C</th>
                    <th>2°2C</th>
                    <th>3°1C</th>
                    <th>3°2C</th> 
                    <th>Opciones</th>
                </tr>
              </thead>";
        echo "<tbody>";

        foreach ($student_data as $student) {
            echo "<tr>
                    <td>{$student['nombres']}</td>
                    <td>{$student['apellidos']}</td>
                    <td>{$student['numero_documento']}</td>
                    <td>{$student['tramos']['1°1C']}</td>
                    <td>{$student['tramos']['1°2C']}</td>
                    <td>{$student['tramos']['2°1C']}</td>
                    <td>{$student['tramos']['2°2C']}</td>
                    <td>{$student['tramos']['3°1C']}</td>
                    <td>{$student['tramos']['3°2C']}</td>
                    <td><a href='" . admin_url("admin.php?page=fines-plugin-administrar-persona-page&persona_id={$student['persona_id']}") . "' class='button'>Detalle</a></td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron alumnos para esta comisión.</p>";
    }

    echo "<a href='javascript:history.back();' class='button'>Volver</a>";
    echo "</div>";
}
