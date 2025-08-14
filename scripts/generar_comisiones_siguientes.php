<?php

//Eliminar todas las calificaciones del curso

require_once '../db-config.php';

use Fines2\AlumnoComision_;
use Fines2\Comision;
use Fines2\Comision_;
use Fines2\Planificacion_;
use Fines2\ComisionDAO;
use Fines2\Curso_;
use Fines2\PlanificacionDAO;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {
    
    $db = DbMy::getInstance();
    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    
    $comisionesAutorizadasSemestreSin32 = ComisionDAO::comisionesAutorizadasSin32ByCalendario(CALENDARIO_ID_ANTERIOR);

    foreach($comisionesAutorizadasSemestreSin32 as $comision) {
      /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

      echo "Procesando Comisión: " . $comision->getLabel() . "<br>";
      
      $tramoSiguiente = $comision->planificacion_?->getTramoSiguiente();
        /** @var Planificacion_ */ $nuevaPlanificacion = DbMy::getInstance()->CreateDataProvider()->fetchEntityByParams("planificacion", [
              "plan" => $comision->planificacion_->plan,
              "anio" => $tramoSiguiente["anio"],
              "semestre" => $tramoSiguiente["semestre"]
          ]);

      if(!empty($comision->comision_siguiente)) {
        /** @var Comision_ */ $comisionSiguiente = $dataProvider->fetchEntityByParams("comision", ["id" => $comision->comision_siguiente]);
        echo "-- Ya tiene comisión siguiente asignada<br>";
        if($comisionSiguiente->planificacion != $nuevaPlanificacion->id) {
          echo "-- La planificacion de la comision siguiente no coincide <br>";
          continue;
        }
      } else {
        $comisionSiguiente = new Comision_();
        $comisionSiguiente->apertura = false;
        $comisionSiguiente->autorizada = false;
        $comisionSiguiente->publicada = false;
        $comisionSiguiente->setFkValue("calendario", CALENDARIO_ID_ACTUAL);
        $comisionSiguiente->division = $comision->division;
        $comisionSiguiente->identificacion = $comision->identificacion;
        $comisionSiguiente->setFk("modalidad", $comision->modalidad_);
        $comisionSiguiente->setFk("planificacion", $nuevaPlanificacion);
        $comisionSiguiente->pfid = $comision->pfid;
        $comisionSiguiente->setFk("sede", $comision->sede_);
        $comisionSiguiente->turno = $comision->turno;

        echo "-- Nueva Comisión Siguiente: " . $comisionSiguiente->getLabel() . "<br>";
        $modifyQueries->buildInsertSql($comisionSiguiente);
        
        $comision->comision_siguiente = $comisionSiguiente->id;
        $modifyQueries->buildUpdateKeySqlById($comision, "comision_siguiente");
        

        $cantidadCursos = $dataProvider->countByParams("curso", ["comision" => $comisionSiguiente->id]);
        if($cantidadCursos > 0) {
          echo "-- La comisión tiene " . $cantidadCursos . " cursos, no se crearan nuevos<br>";
        } else {
          $disposiciones = $dataProvider->fetchAllEntitiesByParams("disposicion", ["planificacion" => $comisionSiguiente->planificacion]);
          foreach($disposiciones as $disposicion) {
            $curso = new Curso_();
            $curso->setFk("comision", $comisionSiguiente);
            $curso->setFk("disposicion", $disposicion);
            $modifyQueries->buildInsertSql($curso);
          }
        }
      }
    }

    echo "Termine de procesar";
    die();
    $cursos_ids = $dataProvider->fetchAllColumnByParams("curso", "id", ["comision" => $comision_id]);
    /** @var AlumnoComision_[] */ $alumnos_comision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision_id]);
    $calificaciones = $dataProvider->fetchAllEntitiesByParams("calificacion", ["curso" => $cursos_ids]);
    $alumnos_comision_ids = [];
    foreach($alumnos_comision as $alumno_comision) {
      $coincidencia = false;
      foreach($calificaciones as $calificacion) {
        if($calificacion->alumno == $alumno_comision->alumno) {
          echo "Alumno con calificacion aprobada en el curso, no se eliminara " . $calificacion->alumno_?->persona_?->getLabel() ?? $calificacion->alumno . "<br>";
          $coincidencia = true;
          break;
        }
        if(!$coincidencia){
          array_push($alumnos_comision_ids, $alumno_comision->id);
        }
      }

    }

    $modifyQueries->buildDeleteSqlByIds("alumno_comision", ...$alumnos_comision_ids);
    $modifyQueries->execute();

    echo "<p>Alumnos de la comisión eliminados, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}