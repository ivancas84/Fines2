<?php

namespace Fines2;

use \Fines2\Alumno;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\DbMy;
use Exception;
use DateTime;
use Fines2\Calificacion_;

class Alumno_ extends Alumno
{
    /** @var Calificacion[] */
    public array $CalificacionAprobada_ = [];

    /** @var int|null */
    public ?int $CalificacionAprobada_Count = null;

    /** @var Calificacion[] */
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

            $this->AniosCursados = array_values($this->AniosCursados);
        }
    }
}