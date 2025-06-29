<?php


function cp_constancia_pase_page() {
    $persona_id = isset($_GET['id_persona']) ? $_GET['id_persona'] : null;
    $wpdb = fines_plugin_db_connect();

    $persona = wpdbPersona__By_id($wpdb, $persona_id);

    if(!$persona){
        echo "<p>No se encontró una persona asociada a ese id.</p>";
        die();
    }

    $alumno = wpdbAlumno__By_idPersona($wpdb, $persona_id);

    if(!$alumno){
        echo "<p>No se encontró un alumno asociado a ese id.</p>";
        die();
    }

    if(!$alumno->plan){
        echo "<p>Alumno sin plan, es necesario completar el plan del alumno para generar la constancia de pase.</p>";
        die();
    }

    if($alumno->anio_ingreso){
        $tramo = $alumno->anio_ingreso;
        if($alumno->semestre_ingreso)
            $tramo .= $alumno->semestre_ingreso;
        else 
            $tramo .= "1";
    } else {
        $tramo = "11";
    }

    $calificaciones_aprobadas_ = wpdbCalificacionesAprobadas__By_idAlumno_idPlan_tramo($wpdb, $alumno->id, $alumno->plan, $tramo);
    $anios_cursados_ = [];
    foreach($calificaciones_aprobadas_ as $calificacion){
        if($calificacion->anio == "1")
            $anios_cursados_[0] = "Primero";
        elseif($calificacion->anio == "2")
            $anios_cursados_[1] = "Segundo";
        elseif($calificacion->anio == "3")
            $anios_cursados_[2] = "Tercero";
    }

    $anios_cursados = implode(", ", $anios_cursados_);
    $calificaciones_aprobadas = json_encode($calificaciones_aprobadas_);
    $calificaciones_desaprobadas = json_encode(wpdbCalificacionesDesaprobadas__By_idAlumno_idPlan_tramo($wpdb, $alumno->id, $alumno->plan, $tramo));

    $nombres = $persona->nombres;
    $apellidos = $persona->apellidos;  
    $numero_documento = $persona->numero_documento;
    $orientacion = $alumno->orientacion;
    $resolucion = $alumno->resolucion;

	$fecha = fechaActualDiaDeMesDeAnio();
    $presentacion = "Quien corresponda";
    $observaciones = "";
	
	
    include plugin_dir_path(__FILE__) . 'cp_constancia_pase_form.html';
}


