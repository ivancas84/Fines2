<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\Entity;
use Fines2\CalificacionDAO;
use Fines2\Alumno_;
use SqlOrganize\Sql\ModifyQueries;

class AlumnoDAO
{
    public static function createAndPersist(ModifyQueries $modifyQueries, string $persona_id, ?string $plan_id): Alumno_{
        /** @var Alumno_ */ $alumno = Alumno_::createByUnique("Fines2\Alumno_", ["persona"=>$persona_id]);
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

    public static function tramo($alumno){

        if(!empty($alumno["anio_ingreso"])){
                $tramo = $alumno["anio_ingreso"];
                    if(!empty($alumno["semestre_ingreso"]))
                        $tramo .= $alumno["semestre_ingreso"];
                    else 
                        $tramo .= "1";

                return $tramo;
        } 
        
        return "11";
    }


    public static function reestructurarCalificacionesByAlumno($alumno){
        $db = DbMy::getInstance();
        $modifyQueries = $db->CreateModifyQueries();
        $idsCalificacionesDesaprobadas = CalificacionDAO::idsCalificacionesDesaprobadasByAlumno($alumno['alumno_id']);
        if(!empty($idsCalificacionesDesaprobadas)){
            //$modifyQueries->buildDeleteSqlByIds("calificacion", ...$idsCalificacionesDesaprobadas);)
        }

        if(!empty($alumno["plan"])){
            if(!empty($alumno["anio_ingreso"])){
                $tramo = $alumno["anio_ingreso"];
                    if(!empty($alumno["semestre_ingreso"]))
                        $tramo .= $alumno["semestre_ingreso"];
                    else 
                        $tramo .= "1";
            } else {
                $tramo = "11";
            }

            $calificacionesAprobadas = CalificacionDAO::calificacionesAprobadasByAlumnoPlanTramo($alumno['alumno_id'], $alumno['plan'], $tramo);
            $disposiciones = DisposicionDAO::disposicionesByPlanTramo($alumno['plan'], $tramo);

            $count = 0;
            foreach($disposiciones as $disposicion){
                $existe = false;
                foreach($calificacionesAprobadas as $calificacion){
                    if($calificacion["disposicion"] == $disposicion["disposicion_id"])
                    {
                        $existe = true;
                        break;
                    }   
                }
                if(!$existe){
                    $count++;
                    PdoFines::InsertFields_("calificacion", CalificacionDAO::getFields(), array(
                        'calificacion_id' => uniqid(),
                        'disposicion' => $disposicion["disposicion_id"],
                        'archivado' => 0,
                        'alumno' => $alumno['alumno_id']
                    ));
                }
            }
            
                
        }
            
    }

}