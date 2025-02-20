<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function sqlSelectComision(){
	return "SELECT
					comision.id as comision_id,
					sede.id as sede_id,
					sede.nombre,
                    CONCAT(
                        'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                        'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                        'N° ', COALESCE(domicilio.numero, '-'), ', ',
                        COALESCE(domicilio.barrio, '-'), ', ',
                        COALESCE(domicilio.localidad, '-')
                    ) AS domicilio,
                    CONCAT(planificacion.anio,'°',planificacion.semestre,'C') AS tramo,
                    plan.orientacion, plan.resolucion,
                    comision.autorizada, comision.apertura, comision.publicada,
                    comision.pfid,
                    GROUP_CONCAT(
                        DISTINCT '* ', persona.nombres, ' ',
                        COALESCE(persona.apellidos, '-'), ' ',
                        COALESCE(persona.telefono, '-'), ' ',
                        COALESCE(persona.email, '-') 
                        SEPARATOR '<br/>'
                    ) AS referentes
                FROM comision     
                INNER JOIN sede ON comision.sede = sede.id
                LEFT JOIN domicilio ON sede.domicilio = domicilio.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN plan ON planificacion.plan = plan.id
                LEFT JOIN designacion ON comision.sede = designacion.sede AND designacion.cargo = 1 AND designacion.hasta IS NULL
                LEFT JOIN persona ON designacion.persona = persona.id";
}

function sqlSelectComision_autorizada__By_calendario__Without_tramo32_and_siguiente($idCalendario){
	return "SELECT
				comision.*, CONCAT(planificacion.anio,planificacion.semestre) as tramo, planificacion.plan
			FROM comision     
			INNER JOIN planificacion ON comision.planificacion = planificacion.id
			WHERE comision.calendario = '$idCalendario'
			AND comision.autorizada = true
			AND CONCAT(planificacion.anio,planificacion.semestre) != '32'
			AND comision_siguiente IS NULL
			";
}

function sqlSelectPlanificacion__By_plan_And_tramo($idPlan, $idTramo){
	return "SELECT
				planificacion.*
			FROM planificacion
			WHERE planificacion.plan = '$idPlan'
			AND CONCAT(planificacion.anio,planificacion.semestre) = '$idTramo'";
}

function wpdbPersona__By_id($wpdb, $persona_id){
    return $wpdb->get_row(
        $wpdb->prepare("SELECT *
                        FROM persona                        
                        WHERE persona.id = '{$persona_id}'")
    );
}

function wpdbDetalles__By_idPersona($wpdb, $persona_id){
    return $wpdb->get_results(
        $wpdb->prepare("
            SELECT * FROM detalle_persona 
            INNER JOIN file ON (detalle_persona.archivo = file.id)
            WHERE persona  = '$persona_id'
        ")
    );
}
function wpdbAlumno__By_idPersona($wpdb, $persona_id){
    return $wpdb->get_row(
        $wpdb->prepare("
            SELECT alumno.*,
            CONCAT(plan.orientacion, ' ', plan.resolucion) AS detalle_plan
            FROM alumno 
            LEFT JOIN plan ON (alumno.plan = plan.id)
            WHERE persona = '$persona_id'
        ")
    );
}

function wpdbComisiones__By_idAlumno($wpdb, $alumno_id){
    return $wpdb->get_results(
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
            
        WHERE alumno = '$alumno_id'
        ORDER BY calendario.anio DESC, calendario.semestre DESC
        LIMIT 100
        ")
    );
}

function wpdbCalificaciones__by_idAlumno($wpdb, $alumno_id){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT 
            calificacion.nota_final, 
            calificacion.crec, 
            CONCAT(planificacion.anio, planificacion.semestre) AS tramo, 
            plan.orientacion, 
            plan.resolucion,
            asignatura.nombre,
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
        WHERE alumno = '$alumno_id'
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}

?>