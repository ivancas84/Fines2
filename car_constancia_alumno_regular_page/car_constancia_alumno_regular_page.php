<?php


function car_constancia_alumno_regular_page() {
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

    $alumno_comision = wpdbUltimoAlumnoComisionActivo__By_idAlumno($wpdb, $alumno->id);

    if(!$alumno_comision){
        echo "<p>No se encontró una comisión activa asociada a ese alumno.</p>";
        die();
    }

    $nombres = $persona->nombres;
    $apellidos = $persona->apellidos;  
    $numero_documento = $persona->numero_documento;
    $anio_en_curso = toOrdinalSpanish(intval($alumno_comision->planificacion_anio));
    $orientacion = $alumno_comision->orientacion;
    $resolucion = $alumno_comision->resolucion;
    $fecha = fechaActualDiaDeMesDeAnio();
    $presentado = "Quien corresponda";
    $observaciones = "";

    include plugin_dir_path(__FILE__) . 'car_constancia_alumno_regular_form.html';
}


