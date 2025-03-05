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
                    comision.autorizada, comision.apertura, comision.publicada, comision.turno,
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

function wpdbComisiones_autorizadas__By_calendario__Without_tramo32_and_siguiente($wpdb, $idCalendario){
	return $wpdb->get_results($wpdb->prepare("SELECT
				comision.*, CONCAT(planificacion.anio,planificacion.semestre) as tramo, planificacion.plan
			FROM comision     
			INNER JOIN planificacion ON comision.planificacion = planificacion.id
			WHERE comision.calendario = '$idCalendario'
			AND comision.autorizada = true
			AND CONCAT(planificacion.anio,planificacion.semestre) != '32'
			AND comision_siguiente IS NULL
			"));
}

function wpdbComisiones_autorizadas__By_calendario__Without_tramo32($wpdb, $idCalendario){
	return $wpdb->get_results($wpdb->prepare("SELECT
				comision.*, CONCAT(planificacion.anio,planificacion.semestre) as tramo, planificacion.plan
			FROM comision     
			INNER JOIN planificacion ON comision.planificacion = planificacion.id
			WHERE comision.calendario = '$idCalendario'
			AND comision.autorizada = true
			AND CONCAT(planificacion.anio,planificacion.semestre) != '32'
			"));
}

function wpdbAlumnosComision__By_calendario__With_ComisionAutorizada__Without_tramo32($wpdb, $idCalendario){
	return $wpdb->get_results($wpdb->prepare("
            SELECT * FROM alumno_comision
            INNER JOIN comision ON alumno_comision.comision = comision.id
			INNER JOIN planificacion ON comision.planificacion = planificacion.id
			WHERE comision.calendario = '$idCalendario'
			AND comision.autorizada = true
			AND CONCAT(planificacion.anio,planificacion.semestre) != '32'
			"));
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

function wpdbPersona__By_numeroDocumento($wpdb, $numero_documento){
    return $wpdb->get_row(
        $wpdb->prepare("SELECT *
                        FROM persona                        
                        WHERE persona.numero_documento = '{$numero_documento}'")
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
            CONCAT(plan.orientacion, ' ', plan.resolucion) AS detalle_plan,
            plan.orientacion, plan.resolucion
            FROM alumno 
            LEFT JOIN plan ON (alumno.plan = plan.id)
            WHERE persona = '$persona_id'
        ")
    );
}

function wpdbDisposiciones__By_idPlan_tramo($wpdb, $plan_id, $tramo){
    return $wpdb->get_results(
        $wpdb->prepare("
            SELECT DISTINCT disposicion.*,  CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura
            FROM disposicion
            INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
            INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
            WHERE planificacion.plan = %s AND CONCAT(planificacion.anio, planificacion.semestre) >= %s
        ", $plan_id, $tramo)
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

/****** calificacion *****/
function wpdbIdsCalificacionesDesaprobadas__By_idAlumno($wpdb, $alumno_id){
    return $wpdb->get_col(
        $wpdb->prepare("
            SELECT DISTINCT id 
            FROM calificacion
            WHERE (nota_final < 7 OR nota_final IS NULL) AND (crec < 4 OR crec IS NULL) AND alumno = %s
        ", $alumno_id)
    );;
}

function wpdbDeleteCalificaciones__By_ids($wpdb, $ids){
    $ids = implode("','", $ids);
    $sql = "DELETE FROM calificacion WHERE id IN ('$ids')";
    return $wpdb->query(
        $wpdb->prepare($sql)
    );
}

function wpdbCalificacionesAprobadas__by_idAlumno($wpdb, $alumno_id){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
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
        WHERE alumno = '$alumno_id' AND (nota_final >= 7 OR crec >= 4)
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}

function wpdbCalificacionesAprobadas__By_idAlumno_idPlan_tramo($wpdb, $alumno_id, $plan_id, $tramo){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
            calificacion.id,
            calificacion.disposicion,
            calificacion.nota_final, 
            calificacion.crec, 
            CONCAT(planificacion.anio, planificacion.semestre) AS tramo, 
            planificacion.anio AS anio, planificacion.semestre AS semestre,
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
        WHERE alumno = '$alumno_id' AND plan.id = '$plan_id' 
        AND CONCAT(planificacion.anio, planificacion.semestre) >= '$tramo'
        AND (nota_final >= 7 OR crec >= 4)
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}

function wpdbCalificacionesDesaprobadas__By_idAlumno_idPlan_tramo($wpdb, $alumno_id, $plan_id, $tramo){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
            calificacion.id,
            calificacion.disposicion,
            calificacion.nota_final, 
            calificacion.crec, 
            CONCAT(planificacion.anio, planificacion.semestre) AS tramo, 
            planificacion.anio AS anio, planificacion.semestre AS semestre,
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
        WHERE alumno = '$alumno_id' AND plan.id = '$plan_id' 
        AND CONCAT(planificacion.anio, planificacion.semestre) >= '$tramo'
        AND ((nota_final < 7 OR nota_final IS NULL) AND (crec < 4 OR crec IS NULL))
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}


function wpdbCalificaciones__By_idAlumno_idPlan_tramo($wpdb, $alumno_id, $plan_id, $tramo){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
            calificacion.id,
            calificacion.disposicion,
            calificacion.nota_final, 
            calificacion.crec, 
            CONCAT(planificacion.anio, planificacion.semestre) AS tramo, 
            planificacion.anio AS anio, planificacion.semestre AS semestre,
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
        WHERE alumno = '$alumno_id' AND plan.id = '$plan_id' AND CONCAT(planificacion.anio, planificacion.semestre) >= '$tramo'
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}

function wpdbCalificacionesAprobadas__by_idAlumno_notIdPlan($wpdb, $alumno_id, $plan_id){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura,
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
        WHERE alumno = '$alumno_id' AND plan.id != '$plan_id' AND (nota_final >= 7 OR crec >= 4)
        ORDER BY planificacion.anio, planificacion.semestre
        LIMIT 100
        ")
    );
}

function wpdbUltimoAlumnoComisionActivo__By_idAlumno($wpdb, $alumno_id){
    return $wpdb->get_row(
        $wpdb->prepare("
            SELECT planificacion.anio AS planificacion_anio, plan.orientacion, plan.resolucion,
			CONCAT(sede.nombre, ' ', domicilio.calle,  ' N° ', domicilio.numero, ' e/ ', domicilio.entre, ' ', domicilio.localidad) AS sede_detalle,
			CONCAT(calendario.anio, '-', calendario.semestre) AS periodo,
            comision.pfid
			FROM alumno_comision
            INNER JOIN comision ON alumno_comision.comision = comision.id
			INNER JOIN sede ON comision.sede = sede.id
			INNER JOIN domicilio ON sede.domicilio = domicilio.id
			INNER JOIN planificacion ON comision.planificacion = planificacion.id
			INNER JOIN plan ON planificacion.plan = plan.id
            INNER JOIN calendario ON comision.calendario = calendario.id
            WHERE alumno_comision.alumno = '$alumno_id' AND alumno_comision.estado = 'Activo'
            ORDER BY calendario.anio DESC, calendario.semestre DESC;
        ")
    );
}


function wpdbAlumnosConTodasLasCalificacionesAprobadas__By_comisionPfid($wpdb, $pfid){
    // Step 2: Get student data and grades
    $query_students = "
        SELECT 
            DISTINCT persona.id AS persona_id, 
            persona.nombres, 
            persona.apellidos, 
            persona.numero_documento, 
            alumno.estado_inscripcion,
            alumno.tiene_dni, 
            alumno.tiene_certificado, 
            alumno.tiene_constancia, 
            alumno.tiene_partida, 
            alumno.previas_completas, 
            alumno.confirmado_direccion, 
            alumno.anio_ingreso,
            CONCAT(plan.orientacion, ' ', plan.resolucion) AS detalle_plan,
            calificacion_aprobada.detalle_asignatura, 
            calificacion_aprobada.max_nota_final, 
            calificacion_aprobada.max_crec
        FROM alumno
        INNER JOIN persona ON alumno.persona = persona.id
        INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
        LEFT JOIN plan ON alumno.plan = plan.id
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
    return $wpdb->get_results($wpdb->prepare($query_students, $pfid));
}

function wpdbAlumnosConCantidadCalificacionesAprobadas__By_comisionId__Join_alumnoPlan($wpdb, $comision_id){
    $wpdb->get_results(
        $wpdb->prepare("
            SELECT alumno.id, alumno.anio_ingreso, alumno.tiene_dni, alumno.tiene_certificado, alumno.tiene_constancia, alumno.previas_completas, alumno.confirmado_direccion, alumno.tiene_partida, 
            persona.id AS persona_id, persona.nombres, persona.apellidos, persona.numero_documento, 
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
}

function wpdbCantidadCalificacionesAprobadas3oMas__Group_alumno_planificacion__By_comision($wpdb, $comision_id){
    return $wpdb->get_results(
        $wpdb->prepare("
        SELECT DISTINCT alumno.id, alumno.plan, comision.planificacion, calificacion_aprobada.cantidad_aprobadas, persona.numero_documento
			FROM alumno
            INNER JOIN persona ON (alumno.persona = persona.id)
			INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
            INNER JOIN comision ON comision.id = alumno_comision.comision
			LEFT JOIN (
					SELECT calificacion.alumno, disposicion.planificacion,
                	COUNT(*) AS cantidad_aprobadas 
					FROM calificacion
					INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
					INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)					
					WHERE (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                	GROUP BY calificacion.alumno, disposicion.planificacion
			) AS calificacion_aprobada ON calificacion_aprobada.alumno = alumno.id AND calificacion_aprobada.planificacion = comision.planificacion
            WHERE comision.id = '$comision_id' AND calificacion_aprobada.cantidad_aprobadas >= 3;"
			)
    );
}

function wpdbIdsAlumnos__By_comision($wpdb, $comision_id){
    return $wpdb->get_col(
        $wpdb->prepare("
            SELECT DISTINCT alumno
			FROM alumno_comision 
            WHERE comision = '$comision_id';"
        )
    );
}

function wpdbUpdateTableKeyValue__By_id($wpdb, $table, $field, $value, $id, $valueFormat ="%s", $idFormat ="%s"){
    // Update the database
    return $wpdb->update(
        $table,
        [$field => $value],
        ["id" => $id],
        [$valueFormat],  // Value format
        [$idFormat]   // ID format
    );
}

function wpdbIdSedes__By_calendario($wpdb, $idCalendario){
    $sql =  "
        SELECT sede.id FROM sede
        INNER JOIN comision ON (comision.sede = sede.id)
        WHERE comision.calendario = %s
        ";

    $wpdb->get_col($wpdb->prepare($sql, $idCalendario));
}

function wpdbSedes__By_ids($wpdb, $ids, $order_by){
    $sql =  "
        SELECT sede.id, sede.nombre, sede.numero, sede.fecha_traspaso,
        CONCAT(domicilio.calle, ' N° ', domicilio.numero,' e/ ', domicilio.entre, ' ', domicilio.barrio, ' ', domicilio.localidad) AS detalle_domicilio,
        centro_educativo.nombre AS detalle_centro_educativo
        FROM sede
        LEFT JOIN centro_educativo ON (sede.centro_educativo = centro_educativo.id)
        LEFT JOIN domicilio ON (sede.domicilio = domicilio.id)
        WHERE sede.id IN (%s)
        ORDER BY %s
        ";

    return $wpdb->get_results($wpdb->prepare($sql, implode(",", $ids), $order_by));
}

function wpdbSedes($wpdb, $order_by){
    $sql =  "
        SELECT sede.id, sede.nombre, sede.numero, sede.fecha_traspaso,
        CONCAT(domicilio.calle, ' N° ', domicilio.numero,' e/ ', domicilio.entre, ' ', domicilio.barrio, ' ', domicilio.localidad) AS detalle_domicilio,
        centro_educativo.nombre AS detalle_centro_educativo
        FROM sede
        LEFT JOIN centro_educativo ON (sede.centro_educativo = centro_educativo.id)
        LEFT JOIN domicilio ON (sede.domicilio = domicilio.id)
        ORDER BY %s
        ";

    return $wpdb->get_results($wpdb->prepare($sql, $order_by));
}

function wpdbDesignaciones__By_sede($wpdb, $sede_id){
    return $wpdb->get_results(
        $wpdb->prepare("
            SELECT designacion.*, 
            persona.nombres, persona.apellidos, persona.telefono, persona.email,
            cargo.descripcion AS cargo_descripcion
            FROM designacion
            INNER JOIN persona ON (designacion.persona = persona.id)
            INNER JOIN cargo ON (designacion.cargo = cargo.id)
            WHERE designacion.sede = %s" , $sede_id)
    );
}
?>