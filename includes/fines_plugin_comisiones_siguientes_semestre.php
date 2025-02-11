<?php

/**
 * Generar comisiones del semestre siguiente
 */
function fines_plugin_comisiones_siguientes_semestre() {
    $wpdb = fines_plugin_db_connection();

    if (!$wpdb) {
        echo "<p style='color: red;'>Error de conexión a la base de datos.</p>";
        return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? $_GET['calendario'] : '';
	$selected_calendario_siguiente = isset($_GET['calendario_siguiente']) ? $_GET['calendario_siguiente'] : '';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . '../html/fines_plugin_comisiones_siguientes_semestre_formulario.php';

    if (isset($_GET['submit']) && !empty($_GET['calendario']) && !empty($_GET['calendario_siguiente'])) {
          
        $calendario_id = $_GET['calendario'];
            
             // Build SQL query
            $sql = sqlSelectComision_autorizada__By_calendario__Without_tramo32_and_siguiente($calendario_id);
               
            // Execute query
            $comisiones = $wpdb->get_results($wpdb->prepare($sql));
	
            if (!$comisiones) {
                echo "<p style='color: red;'>No se encontraron comisiones para el calendario seleccionado.</p>";
                return;
            }

            $planificaciones = array();
			
            foreach($comisiones as $comision){
                $tramo_siguiente = tramoSiguiente($comision->tramo);
                $plan_key = $comision->plan . $tramo_siguiente;

                if (!isset($planificaciones[$plan_key])) {
                    $sql = sqlSelectPlanificacion__By_plan_And_tramo($comision->plan, $tramo_siguiente);
                    $planificacion = $wpdb->get_row($wpdb->prepare($sql));
                    
                    if (!$planificacion) {
                        echo "<p style='color: orange;'>No se encontró planificación para el plan: {$comision->plan} y tramo: {$tramo_siguiente}.</p>";
                        continue;
                    }

                    $planificaciones[$plan_key] = $planificacion->id;
                }
    
                $id = uniqid(); 
                $insert_result = $wpdb->insert('comision', array(
                    'id' => $id,
                    'sede' => $comision->sede,
                    'planificacion' => $planificaciones[$plan_key],
                    'calendario' => $selected_calendario_siguiente,
                    'autorizada' => 1,
                    'apertura' => 0,
                    'publicada' => 1,
                    'modalidad' => $comision->modalidad,
                    'turno' => $comision->turno,
                    'division'=>$comision->division,
                    'identificacion' => $comision->identificacion,
                    'pfid' => $comision->pfid,
                ),
                array('%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s')
                );

                if ($insert_result === false) {
                    echo "<p style='color: red;'>Error al insertar la comisión : {$comision->pfid} {$tramo_siguiente} ({$id}). <br><strong>Detalles:</strong> " . esc_html($wpdb->last_error) . "</p>";
                    continue;
                } else {
                    echo "<p style='color: green;'>Comisión insertada: {$comision->pfid} {$tramo_siguiente} ({$id}).</p>";
                }
    
                $update_result = $wpdb->update(
                    'comision', // Table name
                    ['comision_siguiente' => $id], // Data to update (column => new value)
                    ['id' => $comision->id], // WHERE condition (column => value)
                    ['%s'], // Data format (use '%d' for integers, '%s' for strings)
                    ['%s']  // WHERE format (assuming 'id' is an integer)
                );

                if ($update_result === false) {
                    echo "<p style='color: red;'>Error al actualizar la comisión {$comision->pfid} {$comision->tramo} ({$comision->id}). <br><strong>Detalles:</strong> " . esc_html($wpdb->last_error) . "</p>";
                } else {
                    echo "<p style='color: green;'>Comisión actualizada: {$comision->pfid} {$comision->tramo} ({$comision->id}).</p>";
                }
			}
			
    } else {
        echo "<p>Complete el formulario y seleccione ambos calendarios.</p>";
    	}

    echo "</div>";
}