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
            $this->crec = $nota;
        else 
            $this->nota_final = $nota;
    }

    public static function createAndPersistAprobadaByUnique(string $idAlumno, string $idDisposicion, ?string $idCurso, int $nota): Entity {
        $dataProvider = DbMy::getInstance()->CreateDataProvider();
        $modifyQueries = DbMy::getInstance()->CreateModifyQueries();

        /** @var Calificacion_ */ $calificacion = $dataProvider->fetchEntityByParams("calificacion", ["alumno" => $idAlumno, "disposicion" => $idDisposicion]);

        if($calificacion->_status < 0) {//no existe persona, crearla
            $calificacion->alumno = $idAlumno;
            $calificacion->disposicion = $idDisposicion;
            $calificacion->curso = $idCurso;
            $calificacion->setNotaAprobada($nota);
            $modifyQueries->buildInsertSql($calificacion);
            echo ' - Calificacion agregada, id '. $calificacion->id . '<br>';
        
        } else { //existe persona, verificar datos
            if(!empty($calificacion->nota_final) && $calificacion->nota_final >= 7 ){
                echo ' - Ya estaba aprobado con '. $calificacion->nota_final . '<br>';
            }

            if(!empty($calificacion->crec) && $calificacion->crec >= 4 ){
                echo ' - Ya estaba aprobado con '. $calificacion->crec . ' crec<br>';
            }

            if(!empty($calificacion->curso) && $calificacion->curso != $idCurso ){
                echo ' - Estaba en otro curso  '. $calificacion->curso . ' crec<br>';
            }

            $calificacion->alumno = $idAlumno;
            $calificacion->disposicion = $idDisposicion;
            $calificacion->curso = $idCurso;
            $calificacion->SetNotaAprobada($nota);
            $modifyQueries->buildUpdateSql($calificacion);
            echo " - Calificacion actualizada id ". $calificacion->id . "<br>";
        }

        $modifyQueries->process();

        return $calificacion;
    }
}

