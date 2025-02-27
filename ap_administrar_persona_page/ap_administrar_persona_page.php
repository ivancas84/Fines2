<?php

function ap_administrar_persona_page() {
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;

    $wpdb = fines_plugin_db_connect();

    // Fetch student details from the database
    $persona = wpdbPersona__By_id($wpdb, $persona_id);

    $planes = $wpdb->get_results("SELECT * FROM plan");
    $selected_plan = null;

    $resoluciones = $wpdb->get_results("SELECT * FROM plan ORDER BY resolucion");
    $selected_resolucion = null;

    $estados = $wpdb->get_col("SELECT DISTINCT estado_inscripcion FROM alumno ORDER BY estado_inscripcion");
    $selected_estado = null;
    
    $nombres = null;
    $apellidos = null;
    $fecha_nacimiento = null;
    $numero_documento = null;
    $cuil = null;
    $genero = null;
    $telefono = null;
    $email = null;
    $email_abc = null;
    $lugar_nacimiento = null;


    if($persona){
        $nombres = $persona->nombres;
        $apellidos = $persona->apellidos;
        $fecha_nacimiento = $persona->fecha_nacimiento;
        $numero_documento = $persona->numero_documento;
        $cuil = $persona->cuil;
        $genero = $persona->genero;
        $telefono = $persona->telefono;
        $email = $persona->email;
        $email_abc = $persona->email_abc;
        $lugar_nacimiento = $persona->lugar_nacimiento;
            
        $anio_ingreso = null;
        $semestre_ingreso = null;
        $anio_insripcion = null;
        $semestre_inscripcion = null;
        $estado_inscripcion = null;
        $establecimiento_inscripcion = null;
        $observaciones = null;
        $tiene_dni = 0;
        $tiene_constancia = 0;
        $tiene_certificado = 0;
        $tiene_partida = 0;
        $previas_completas = 0;
        $confirmado_direccion = 0;

        include plugin_dir_path(__FILE__) . 'ap_persona_form.html';

        $alumno = wpdbAlumno__By_idPersona($wpdb, $persona_id);

        echo "<h2>Alumno</h2>";


        if ($alumno){
            echo "<ul>";
            echo '<li><a class="btn" href="' . admin_url('admin.php?page=fines-plugin-constancia-alumno-regular-page&id_persona=' . $persona->id) . '" class="btn">Constancia de Alumno Regular</a></li>';
            echo '<li><a class="btn" href="' . admin_url('admin.php?page=fines-plugin-constancia-pase-page&id_persona=' . $persona->id) . '" class="btn">Constancia de Pase</a></li>';

            echo "</ul>";

            $alumno_id = $alumno->id;
            $selected_plan = $alumno->plan;
            $selected_resolucion = $alumno->resolucion_inscripcion;
            $selected_estado = $alumno->estado_inscripcion;
         
            $anio_ingreso = $alumno->anio_ingreso;
            $semestre_ingreso = $alumno->semestre_ingreso;
            $anio_insripcion = $alumno->anio_inscripcion;
            $semestre_inscripcion = $alumno->semestre_inscripcion;
            $estado_inscripcion = $alumno->estado_inscripcion;
            $establecimiento_inscripcion = $alumno->establecimiento_inscripcion;
            $observaciones = $alumno->observaciones;
            $tiene_dni = $alumno->tiene_dni;
            $tiene_constancia = $alumno->tiene_constancia;
            $tiene_certificado = $alumno->tiene_certificado;
            $tiene_partida = $alumno->tiene_partida;
            $previas_completas = $alumno->previas_completas;
            $confirmado_direccion = $alumno->confirmado_direccion;

            $comisiones = wpdbComisiones__By_idAlumno($wpdb, $alumno->id);


            include plugin_dir_path(__FILE__) . 'ap_alumno_form.html';


            echo "<h2>Comisiones</h2>";

            if ($comisiones) {
                include plugin_dir_path(__FILE__) . 'ap_comisiones_table.html';
            } else {
                echo "<p>No se encontraron comisiones para este alumno.</p>";
            }

            echo "<h2>Calificaciones</h2>";

            $idsCalificacionesDesaprobadas = wpdbIdsCalificacionesDesaprobadas__By_idAlumno($wpdb, $alumno->id);
            if($idsCalificacionesDesaprobadas){
                echo "<p>Se van a eliminar " . count($idsCalificacionesDesaprobadas) . " calificaciones desaprobadas.</p>";
                wpdbDeleteCalificaciones__By_ids($wpdb, $idsCalificacionesDesaprobadas);

            }

            if($alumno->plan){
                if($alumno->anio_ingreso){
                    $tramo = $alumno->anio_ingreso;
                    if($alumno->semestre_ingreso)
                        $tramo .= $alumno->semestre_ingreso;
                    else 
                        $tramo .= "1";
                } else {
                    $tramo = "11";
                }
            

                $calificacionesAprobadas = wpdbCalificacionesAprobadas__By_idAlumno_idPlan_tramo($wpdb, $alumno->id, $alumno->plan, $tramo);
                $disposiciones = wpdbDisposiciones__By_idPlan_tramo($wpdb, $alumno->plan, $tramo);

                $count = 0;
                foreach($disposiciones as $disposicion){
                    $existe = false;
                    foreach($calificacionesAprobadas as $calificacion){
                        if($calificacion->disposicion == $disposicion->id)
                        {
                            $existe = true;
                            break;
                        }   
                    }
                    if(!$existe){
                        $count++;
                        $wpdb->insert('calificacion', array(
                            'id' => uniqid(),
                            'disposicion' => $disposicion->id,
                            'archivado' => 0,
                            'alumno' => $alumno->id
                        ));
                    }
                }
                
                $calificaciones = wpdbCalificaciones__By_idAlumno_idPlan_tramo($wpdb, $alumno->id, $alumno->plan, $tramo);
                $calificaciones_ = wpdbCalificacionesAprobadas__By_idAlumno_notIdPlan($wpdb, $alumno->id, $alumno->plan);
                
                echo "<p><strong> El alumno tiene plan asignado, se van a consultar todas las calificaciones aprobadas para el plan y tramo " . $tramo . "</strong></p>";
                if($count) echo "<p>Se cargaron " . $count . " calificaciones que faltan del alumno</p>";
                if ($calificaciones) {
                    include plugin_dir_path(__FILE__) . 'ap_calificaciones_table.html';
                } else {
                    echo "<p>No se encontraron calificaciones para este alumno.</p>";
                }

                echo "<h2>Calificaciones aprobadas de otro plan</h2>";
                if ($calificaciones_) {

                    include plugin_dir_path(__FILE__) . 'ap_calificaciones_aux_table.html';
                } else {
                    echo "<p>No se encontraron calificaciones aprobadas de otro plan para este alumno.</p>";
                }
            } else {

                echo "<p><strong> El alumno no tiene plan asignado, se van a consultar todas las calificaciones aprobadas </strong></p>";
                $calificaciones = wpdbCalificacionesAprobadas__By_idAlumno($wpdb, $alumno->id);
                if ($calificaciones) {
                    include plugin_dir_path(__FILE__) . 'ap_calificaciones_aux_table.html';
                } else {
                    echo "<p>No se encontraron calificaciones aprobadas para este alumno.</p>";
                }

            }
            

        }

        else {
            echo "<strong>No se encontró un alumno asociado con esta persona.</strong>";
            $alumno_id = null;
            $comisiones = null;
            $calificaciones = null;

            include plugin_dir_path(__FILE__) . 'ap_alumno_form.html';

        }
        

        echo "<h2>Detalles</h2>";


        $detalles = wpdbDetalles__By_idPersona($wpdb, $persona_id);

        if ($detalles) {
            include plugin_dir_path(__FILE__) . '../dp_detalle_persona_page/dp_detalles_table.html';
        } else {
            echo "<p>No se encontraron detalles para esta persona.</p>";
        }

    } else {
        include plugin_dir_path(__FILE__) . 'ap_persona_form.html';

        echo "<p>No se encontró una persona asociada a ese id.</p>";
    }
    echo '<a href="javascript:history.back();" class="button">Volver</a>';
}

include plugin_dir_path(__FILE__) . 'ap_persona_form_handle.php';

include plugin_dir_path(__FILE__) . 'ap_alumno_form_handle.php';

include plugin_dir_path(__FILE__) . 'update_calificacion_handle.php';
