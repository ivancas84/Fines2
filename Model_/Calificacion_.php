<?php

namespace Fines2;

use \Fines2\Calificacion;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;
use SqlOrganize\Sql\DbMy;

class Calificacion_ extends Calificacion
{
    public function SetNotaAprobada(int $nota){
        if($nota < 4 && $nota > 10) 
            throw new Exception("Nota incorrecta");
        if($nota < 7)
            $this->set("crec", $nota);
        else 
            $this->set("nota_final", $nota);
    }

}

