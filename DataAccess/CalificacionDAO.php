<?php

namespace Fines2;

class CalificacionDAO
{

    public static function idsCalificacionesDesaprobadasByAlumno($alumno_id)
    {
        $pdo = new PdoFines();
        $query = "SELECT DISTINCT id 
            FROM calificacion
            WHERE (nota_final < 7 OR nota_final IS NULL) AND (crec < 4 OR crec IS NULL) AND alumno = :alumno_id";
        $stmt = $pdo->pdo->prepare($query);
        $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function calificacionesAprobadasByAlumnoPlanTramo($alumno_id, $plan_id, $tramo)
    {
        $sql = "
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
        ";
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function calificacionesByAlumnoPlanTramo($alumno_id, $plan_id, $tramo){
        $sql = "
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
        ";
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function calificacionesAprobadasByAlumnoNotInPlan($alumno_id, $plan_id){
        $sql = "
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
        ";
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}