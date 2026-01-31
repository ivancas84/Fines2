<?php

namespace Fines2\DataAccess;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;
use Fines2\Model\Calificacion_;

class CalificacionDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, int $nota, string $idAlumno, string $idDisposicion, ?string $idCurso): Calificacion_{
        $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
        /** @var Calificacion_ */ $calificacion = $dataProvider->fetchEntityByParams("calificacion", ["alumno" => $idAlumno, "disposicion" => $idDisposicion]);
        if(empty($calificacion)){
            $calificacion = new Calificacion_();
            $calificacion->_status = -1;

        } else {
            $calificacion->_status = 1;
            $calificacion->_changeLog = [];
        }

        $calificacion->set("alumno", $idAlumno);
        $calificacion->set("disposicion", $idDisposicion);
        $calificacion->set("curso", $idCurso);
        $calificacion->setNotaAprobada($nota);
        $modifyQueries->buildPersistSqlByStatus($calificacion);
        return $calificacion;
    }

    /**
     * @return string[]
     */
    public static function idsCalificacionesDesaprobadasByAlumno($alumno_id): array
    {
        $sql = "SELECT DISTINCT id 
            FROM calificacion
            WHERE (nota_final < 7 OR nota_final IS NULL) AND (crec < 4 OR crec IS NULL) AND alumno = :alumno_id";
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllColumnSqlByParams($sql, 0, ["alumno_id"=>$alumno_id]);
    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasDocente(mixed $docente_id): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN curso ON (calificacion.curso = curso.id)
            INNER JOIN toma ON (curso.id = toma.curso)
            WHERE toma.docente = :docente
            AND (nota_final >= 7 OR crec >= 4)
            AND toma.estado = 'Aprobada' || toma.estado = 'Pendiente'
            AND toma.estado_contralor != 'Modificar'
        ";  

        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["docente" => $docente_id] );
    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasByDisposicionAndDnis(mixed $disposicion, array $dnis): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN persona ON (persona.id = alumno.persona)
            WHERE (nota_final >= 7 OR crec >= 4)
            AND calificacion.disposicion = :disposicion
            AND persona.numero_documento IN (:numero_documento)
        ";  

        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["disposicion" => $disposicion, "numero_documento"=>$dnis] );
    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasByAlumnoPlanTramo(string $alumno, string $plan, string $tramo_short): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
            INNER JOIN plan ON (planificacion.plan = plan.id)
            WHERE alumno = :alumno AND plan.id = :plan 
            AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_short
            AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
        ";  

        /** @var Calificacion_[] */ $calificaciones = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno" => $alumno, "plan"=>$plan, "tramo_short"=>$tramo_short] );
        return self::CompletarTomaActivaEnCalificaciones($calificaciones);

    }



    /**
     * @return Calificacion_[]
     */
    public static function calificacionesByAlumnoPlanTramo(string $alumno, string $plan, string $tramo_short): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
            INNER JOIN plan ON (planificacion.plan = plan.id)
            WHERE alumno = :alumno AND plan.id = :plan AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_short
        ";  

        /** @var Calificacion_[] */ $calificaciones = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno" => $alumno, "plan"=>$plan, "tramo_short"=>$tramo_short] );
        return self::CompletarTomaActivaEnCalificaciones($calificaciones);

    }

     /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasAlumnoPlan(string $alumno, string $plan): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
            INNER JOIN plan ON (planificacion.plan = plan.id)
            WHERE alumno = :alumno AND plan.id = :plan 
            AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
        ";  

        /** @var Calificacion_[] */ $calificaciones = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno" => $alumno, "plan"=>$plan] );
        return self::CompletarTomaActivaEnCalificaciones($calificaciones);

    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasByAlumnoNotInPlan(string $alumno, string $plan): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)
            INNER JOIN plan ON (planificacion.plan = plan.id)
            WHERE alumno = :alumno AND plan.id != :plan AND (nota_final >= 7 OR crec >= 4)
        ";  

        /** @var Calificacion_[] */ $calificaciones = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno" => $alumno, "plan"=>$plan] );
        return self::CompletarTomaActivaEnCalificaciones($calificaciones);

    }

    /**
     * @return Calificacion_[]
     */
    public static function calificacionesAprobadasByAlumno(string $alumno): array {

        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            WHERE alumno = :alumno 
            AND (nota_final >= 7 OR crec >= 4)
        ";  

        return \App\Context::getFinesDb()
            ->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno" => $alumno] );
    }

    

    /**
     * @return Calificacion_[]
     */
    public static function CompletarTomaActivaEnCalificaciones($calificaciones): array {
        $db = \App\Context::getFinesDb();

        /** @var string[] */$idsCursos = ValueTypesUtils::arrayOfName($calificaciones, "curso");

        if(empty($idsCursos)) return $calificaciones;
        $tomasActivas = TomaDAO::TomasActivasByCursos(...$idsCursos);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($calificaciones as &$calificacion){
            if(!empty(($calificacion->curso_) && array_key_exists($calificacion->curso_->id, $tomasActivas)))
                $calificacion->curso_->setFk("toma_activa", $tomasActivas[$calificacion->curso_->id]);
        }

        return $calificaciones;
    }


    public static function CalificacionesAprobadasAlumnoPlanificacion(string $alumno_id, string $planificacion_id): array {
        $sql = "
            SELECT DISTINCT calificacion.id
            FROM calificacion
            INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
            WHERE (nota_final >= 7 OR crec >= 4)
            AND calificacion.alumno = :alumno_id
            AND disposicion.planificacion = :planificacion_id
        ";  
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("calificacion", $sql, ["alumno_id"=>$alumno_id, "planificacion_id"=>$planificacion_id]);
    }
        
}