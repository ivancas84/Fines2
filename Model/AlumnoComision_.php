<?php

namespace Fines2;

use \Fines2\AlumnoComision;
use \Fines2\Comision_;
use SqlOrganize\Sql\DbMy;

use SqlOrganize\Sql\Entity;
use Exception;
use DateTime;

class AlumnoComision_ extends AlumnoComision
{

    /** @var string[] */ public array $notas = [];

    public static function imprimirComisionesByAlumno(string $idAlumno){
        /** @var Comision_[] */ $comisiones = \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesByParams("alumno_comision", ["alumno" => $idAlumno]);
        if(!empty($comisiones)) {
            echo " - Alumno cargado en " . count($comisiones) . " comisiones: <br>";
            foreach($comisiones as $com)
                echo " -- " . $com->getLabel() . "<br>";
        } else {
            echo " - Alumno no tiene comisiones<br>";
        }
    }
}

