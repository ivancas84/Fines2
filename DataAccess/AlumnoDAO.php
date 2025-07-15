<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;
use \Fines2\CalificacionDAO;
use \Fines2\DisposicionDAO;
use \Fines2\Alumno_;
use \SqlOrganize\Sql\ModifyQueries;

class AlumnoDAO
{
    public static function createAndPersist(ModifyQueries $modifyQueries, string $persona_id, ?string $plan_id): Alumno_{
        $alumno = new Alumno_();
        $alumno->initByUnique(["persona"=>$persona_id]);
        $alumno->set("plan", $plan_id);
        $modifyQueries->buildPersistSqlByStatus($alumno);
        return $alumno;
    }
    
    public static function estados_inscripcion(): array {
        $sql = "SELECT DISTINCT estado_inscripcion FROM alumno ORDER BY estado_inscripcion";
        
        return DbMy::getInstance()->CreateDataProvider()->fetchAllColumnSqlByParams($sql, 0);
    }

    public static function alumnoByNumeroDocumento($numero_documento): ?Entity {
        $sql = "SELECT alumno.id
                FROM alumno
                INNER JOIN persona ON alumno.persona = persona.id
                WHERE persona.numero_documento = :numero_documento";
        return DbMy::getInstance()->CreateDataProvider()->fetchEntityBySqlId("alumno", $sql, ['numero_documento' => $numero_documento]);
    }



    public static function reestructurarCalificacionesByAlumno(ModifyQueries $modifyQueries, Alumno_ $alumno){
        $db = DbMy::getInstance();
        /** @var string[] */ $idsCalificacionesDesaprobadas = CalificacionDAO::idsCalificacionesDesaprobadasByAlumno($alumno->id);
        if(!empty($idsCalificacionesDesaprobadas)){
            $modifyQueries->buildDeleteSqlByIds("calificacion", ...$idsCalificacionesDesaprobadas);
        }

        if(!empty($alumno->plan)){
            /** @var string */ $tramo = $alumno->getTramoShort();
            /** @var Calificacion_[] */ $calificacionesAprobadas = CalificacionDAO::calificacionesAprobadasByAlumnoPlanTramo($alumno->id, $alumno->plan, $tramo);
            /** @var Disposicion_[] */ $disposiciones =  DisposicionDAO::disposicionesByPlanTramo($alumno->plan, $tramo);

            $countInsert = 0;
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
                    $countInsert++;
                    $cal = new Calificacion_();
                    $cal->alumno = $alumno->id;
                    $cal->disposicion = $disposicion->id;
                    $cal->archivado = false;
                    $modifyQueries->buildInsertSql($cal);
                }
            }
            
                
        }
            
    }

}