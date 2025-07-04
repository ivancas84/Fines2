<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;

class CalificacionDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, int $nota, string $idAlumno, string $idDisposicion, ?string $idCurso): Calificacion_{
        $dataProvider = DbMy::getInstance()->CreateDataProvider();
        /** @var Calificacion_ */ $calificacion = $dataProvider->fetchEntityByParams("calificacion", ["alumno" => $idAlumno, "disposicion" => $idDisposicion]);
        if(empty($calificacion)){
            $calificacion = new Calificacion_();
        } else {
            $calificacion->_status = 1;
        }
        $calificacion->set("alumno", $idAlumno);
        $calificacion->set("disposicion", $idDisposicion);
        $calificacion->set("curso", $idCurso);
        $calificacion->setNotaAprobada($nota);
        $modifyQueries->buildPersistSqlByStatus($calificacion);
        return $calificacion;
    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasByDisposicionAndDnis(mixed $disposicion, array $numero_documento): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN persona ON (persona.id = alumno.persona)
            WHERE (nota_final >= 7 OR crec >= 4)
            AND calificacion.disposicion = :disposicion
            AND persona.numero_documento IN (:numero_documento)
        ";  

        return DbMy::getInstance()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["disposicion" => $disposicion, "numero_documento"=>$numero_documento] );
    }


    function calificacionesAprobadasByDisposicionAndDnis_($disposicion_id, $numeros_documento, $fetchMode = PDO::FETCH_OBJ) {
        // Step 1: Create placeholders
        $placeholders = [];
        for ($i = 0; $i < count($numeros_documento); $i++)
            $placeholders[] = ":doc$i";

        $stmt = $this->pdo->prepare("
            SELECT DISTINCT calificacion.id, calificacion.nota_final, calificacion.crec, calificacion.curso, calificacion.id AS calificacion_id, alumno.id AS alumno_id, persona.id AS persona_id, persona.numero_documento AS numero_documento
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN persona ON (persona.id = alumno.persona)
            WHERE (nota_final >= 7 OR crec >= 4)
            AND calificacion.disposicion = :idDisposicion
            AND persona.numero_documento IN (" . implode(',', $placeholders) . ")");

        $stmt->bindParam(':idDisposicion', $disposicion_id, PDO::PARAM_STR); // Bind as a string

        for ($i = 0; $i < count($numeros_documento); $i++)
            $stmt->bindValue(":doc$i", $numeros_documento[$i], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll($fetchMode);
    }

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