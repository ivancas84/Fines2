<?php

namespace Fines2\DataAccess;

use App\Context;
use \SqlOrganize\Sql\Entity;
use \Fines2\DataAccess\CalificacionDAO;
use \Fines2\DataAccess\DisposicionDAO;
use \Fines2\Model\Alumno_;
use \Fines2\Model\Comision_;
use \Fines2\Model\Calificacion_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use \SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

class AlumnoDAO
{
    public static function createAndPersist(ModifyQueries $modifyQueries, string $persona_id, ?string $plan_id): Alumno_{
        $alumno = new Alumno_();
        $alumno->initByUnique(["persona"=>$persona_id]);
        $alumno->set("plan", $plan_id);
        $modifyQueries->persistSqlByStatus($alumno);
        return $alumno;
    }
    
    public static function estados_inscripcion(): array {
        $sql = "SELECT DISTINCT estado_inscripcion FROM alumno ORDER BY estado_inscripcion";
        
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllColumnSqlByParams($sql, 0);
    }

    public static function alumnoByNumeroDocumento($numero_documento): ?Alumno_ {
        $sql = "SELECT alumno.id
                FROM alumno
                INNER JOIN persona ON alumno.persona = persona.id
                WHERE persona.numero_documento = :numero_documento";
        /** @var Alumno_ */ $alumno = \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("alumno", $sql, ['numero_documento' => $numero_documento]);
        return $alumno;
    }

    public static function ultimaComisionAlumno(string $alumno_id): ?Comision_ {
        $sql = "SELECT comision.id 
        FROM alumno_comision 
        INNER JOIN alumno ON alumno_comision.alumno = alumno.id 
        INNER JOIN comision ON comision.id = alumno_comision.comision 
        INNER JOIN calendario ON calendario.id = comision.calendario
        WHERE alumno.id = :alumno_id
        AND alumno_comision.activo = 1
        ORDER BY calendario.inicio DESC LIMIT 1;
";
        /** @var ?Comision_ */ $comision = Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("comision", $sql, ['alumno_id' => $alumno_id]);
        return $comision;
    }

    public static function reestructurarCalificacionesByAlumno(ModifyQueries $modifyQueries, Alumno_ $alumno){
        $db = \App\Context::getFinesDb();
        /** @var string[] */ $idsCalificacionesDesaprobadas = CalificacionDAO::idsCalificacionesDesaprobadasByAlumno($alumno->id);
        if(!empty($idsCalificacionesDesaprobadas)){
            $modifyQueries->deleteSqlByIds("calificacion", ...$idsCalificacionesDesaprobadas);
        }

        if(!empty($alumno->plan)){
            /** @var string */ $tramo = $alumno->getTramoIngresoShort();
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
                    $cal->nota_final = 0;
                    $cal->crec = 0;
                    $modifyQueries->insertSql($cal);
                }
            }
            
                
        }
            
    }


    /**
     * @param string[] $id_comisiones
     * @return Alumno_[]
     */
    public static function alumnosComisiones(array $id_comisiones): array{
        $sql = "
            SELECT 
                DISTINCT alumno.id
            FROM alumno
            INNER JOIN persona ON (alumno.persona = persona.id)
            INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
            WHERE comision IN (:id_comisiones)
            ORDER BY persona.apellidos ASC, persona.nombres ASC;
        ";

        /** @var DataProvider */ $dp = Context::getFinesDb()->CreateDataProvider();

         return $dp->fetchAllEntitiesBySqlId("alumno", $sql, ["id_comisiones"=>$id_comisiones]);
    }

}