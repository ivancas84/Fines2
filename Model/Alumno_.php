<?php

namespace Fines2;

use \Fines2\Alumno;

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\DbMy;
use Exception;
use DateTime;

class Alumno_ extends Alumno
{
   public function getTramoShort(){
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
}