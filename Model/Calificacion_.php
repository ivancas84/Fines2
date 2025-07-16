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

    public function getNotaAprobada(){
        if($this->nota_final >= 7)
            return $this->nota_final;
        
        if($this->crec >= 4)
            return $this->crec - "c";
    }

    public function cssBackgroundColor(): string {

        return (intval($this->nota_final) < 7 && intval($this->crec) < 4) ? "background-color: #FFDDDD;" // Red pastel
                        : "background-color: #DDFFDD;"; // Green pastel
    }

}

