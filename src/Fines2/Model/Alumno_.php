<?php

namespace Fines2\Model;

use \Fines2\DataAccess\CalificacionDAO;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;
use SqlOrganize\Utils\ValueTypesUtils;

class Alumno_ extends Alumno
{
  /** @var Calificacion_[] */
    public array $CalificacionAprobada_ = [];

    /** @var int|null */
    public ?int $CalificacionAprobada_Count = null;

    /** @var Calificacion_[] */
    public array $CalificacionDesaprobada_ = [];

    /** @var int|null */
    public ?int $CalificacionDesaprobada_Count = null;

    /** @var string[] */
    public array $AniosCursados = [];

    public function getTramoIngresoShort(){
        if(!empty($this->anio_ingreso)){
            $tramo = strval($this->anio_ingreso);
            if(!empty($this->semestre_ingreso))
                $tramo .= strval($this->semestre_ingreso);
            else 
                $tramo .= "1";
        } else {
            $tramo = "11";
        }

        return $tramo;
   }

   public function initializeCalifacionesArrays(): void {
        /** @var Calificacion_[] */ $calificaciones = CalificacionDAO::calificacionesByAlumnoPlanTramo($this->id, $this->plan, $this->getTramoIngresoShort());
        foreach($calificaciones as $c){
            if($c->getNotaAprobada() != null){
                $this->CalificacionAprobada_[] = $c;
            } else {
                $this->CalificacionDesaprobada_[] = $c;
            }
        }
        $this->CalificacionAprobada_Count = count($this->CalificacionAprobada_);
        $this->CalificacionDesaprobada_Count = count($this->CalificacionDesaprobada_);

        $this->initializeAniosCursados($this->CalificacionAprobada_);
   }

   public function initializeAniosCursados(array $calificaciones): void{
        $this->AniosCursados = [];
        
        foreach($calificaciones as $calificacion){
            $anio = $calificacion->disposicion_->planificacion_->anio;

            if($anio == "1")
                $this->AniosCursados[0] = "Primero";
            elseif($anio == "2")
                $this->AniosCursados[1] = "Segundo";
            elseif($anio == "3")
                $this->AniosCursados[2] = "Tercero";

            $this->AniosCursados = array_unique(array_values($this->AniosCursados));
        }
    }

    public function migrateCalificaciones($modifyQueries, $alumno_destino_id){
        /** @var Calificacion_[] */ $calificaciones_origen = CalificacionDAO::calificacionesAprobadasByAlumno($this->id);
        /** @var array<mixed, Calificacion_> */$calificaciones_origen_agrupadas = ValueTypesUtils::dictOfObjByPropertyNames($calificaciones_origen, "disposicion");
        /** @var Calificacion_[] */ $calificaciones_destino = CalificacionDAO::calificacionesAprobadasByAlumno($alumno_destino_id);
        /** @var array<mixed, Calificacion_> */ $calificaciones_destino_agrupadas = ValueTypesUtils::dictOfObjByPropertyNames($calificaciones_destino, "disposicion");

        foreach($calificaciones_origen_agrupadas as $disposicion => $calificacion){
            if(!array_key_exists($disposicion, array_keys($calificaciones_destino_agrupadas))){
                $calificacion->alumno = $alumno_destino_id;
                $modifyQueries->updateKeySqlById($calificacion, "alumno");
            }
        }
    }
}

