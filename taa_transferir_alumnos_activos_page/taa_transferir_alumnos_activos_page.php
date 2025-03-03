<?php

function taa_transferir_alumnos_activos_page() {
    $wpdb = fines_plugin_db_connect();

    if (!$wpdb) {
		echo "error de conexión";
		return;
    }

	$calendarios = $wpdb->get_results("SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC");
	$selected_calendario = isset($_GET['calendario']) ? sanitize_text_field($_GET['calendario']) : '';

    echo "<div class=\"wrap\">";
    
    include plugin_dir_path(__FILE__) . 'taa_seleccionar_calendario.html';

    if (isset($_GET['submit']) && !empty($_GET['calendario'])) {
            $calendario_id = sanitize_text_field($_GET['calendario']);
            
            $comisiones = wpdbComisiones_autorizadas__By_calendario__Without_tramo32($wpdb, $calendario_id);
            

            foreach($comisiones as $comision){


                echo "<h2>Procesando " . $comision->pfid . "</h2>";
                $idsAlumnosExistentes = wpdbIdsAlumnos__By_comision($wpdb, $comision->id);
                $alumnos = wpdbCantidadCalificacionesAprobadas3oMas__Group_alumno_planificacion__By_comision($wpdb, $comision->id);

                echo "<p>Cantidad de alumnos existentes en la comisión (activos y no activos) " . count($idsAlumnosExistentes) . "</p>";
                echo "<p>Alumnos que aprobaron 3 o más calificaciones " . count($alumnos) . "</p>";

                if(!$comision->comision_siguiente){
                    echo "<p>La comisión siguiente no está definida.</p>";
                    continue;
                }

                $idsAlumnosExistentesSiguiente = wpdbIdsAlumnos__By_comision($wpdb, $comision->comision_siguiente);
                echo "<p>Cantidad de alumnos existentes en la comisión siguiente (activos y no activos) " . count($idsAlumnosExistentesSiguiente) . "</p>";

                $cantidadAlumnosAgregados = 0;

                foreach($alumnos as $alumno){
                    if($alumno->plan != $comision->plan){
                        echo "<p>El alumno " . $alumno->numero_documento . " tiene un plan diferente de la comisión. ";
                        if (wpdbUpdateTableKeyValue__By_id($wpdb, "alumno", "plan", $comision->plan, $alumno->id) !== false) {
                            echo "Se actualizó el plan</p>";
                        } else {
                            echo "Falló al actualizar el plan: " . $wpdb->last_error  . "</p>";
                        }
                    }

                    if(!in_array($alumno->id, $idsAlumnosExistentesSiguiente)){
                        
                        $data = [
                            'id'            => uniqid(), // Generate a unique ID
                            'activo'        => 1,  // Set active status
                            'comision'      => $comision->comision_siguiente,
                            'alumno'        => $alumno->id,
                            'estado'        => 'Activo',
                        ];

                        $format = ['%s', '%d', '%s', '%s', '%s'];

                        $cantidadAlumnosAgregados++;

                        if (!$wpdb->insert("alumno_comision", $data, $format)) {
                            echo "Falló al insertar alumno " . $alumno->numero_documento . ": " . $wpdb->last_error  . "</p>";
                            $cantidadAlumnosAgregados--;
                        }

                        
                    }

                }

                echo "<p>Cantidad de alumnos agregados a la comisión " . $cantidadAlumnosAgregados . "</p>";

            }

            if (!$comisiones) {
                
                echo "<p>No se encontraron comisiones para este calendario.</p>";
                die();
            }
    }

    echo "</div>";
}