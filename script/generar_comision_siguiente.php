<?php

//Eliminar todas las calificaciones del curso

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Fines2\Model\Comision_;
use Fines2\Model\Planificacion_;
use Fines2\Model\Curso_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {
  $comision_id = $_POST["comision_id"];

  if(empty($comision_id)) 
    throw new Exception("No se encuentra definida la comisión");

  $db = \App\Context::getFinesDb();
  /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

  /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["id"=>$comision_id]);

  if(empty($comision))
    throw new Exception("La consulta de comisión no arrojó resultados");

  $tramoSiguiente = $comision->planificacion_?->getTramoSiguiente();

  if(empty($tramoSiguiente))
    throw new Exception("No está definido el tramo siguiente");

  /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
  echo "Procesando Comisión: " . $comision->getLabel() . " (" . $comision->id . ")<br>";
      
    /** @var Planificacion_ */ $nuevaPlanificacion = \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityByParams("planificacion", [
          "plan" => $comision->planificacion_->plan,
          "anio" => $tramoSiguiente["anio"],
          "semestre" => $tramoSiguiente["semestre"]
      ]);

      if(!empty($comision->comision_siguiente)) {
        /** @var Comision_ */ $comisionSiguiente = $dataProvider->fetchEntityByParams("comision", ["id" => $comision->comision_siguiente]);
        echo "-- Ya tiene comisión siguiente asignada " . $comisionSiguiente->id . "<br>";
        if($comisionSiguiente->planificacion != $nuevaPlanificacion->id) {
          echo "-- La planificacion de la comision siguiente no coincide <br>";
        }
      } else {
        $comisionSiguiente = new Comision_();
        $comisionSiguiente->apertura = false;
        $comisionSiguiente->autorizada = true;
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
        $modifyQueries->insertSql($comisionSiguiente);
        
        $comision->comision_siguiente = $comisionSiguiente->id;
        $modifyQueries->updateKeySqlById($comision, "comision_siguiente");
      }

      $cantidadCursos = $dataProvider->countByParams("curso", ["comision" => $comisionSiguiente->id]);
      if($cantidadCursos > 0) {
        echo "-- La comisión tiene " . $cantidadCursos . " cursos, no se crearan nuevos<br>";
      } else {
        $disposiciones = $dataProvider->fetchAllEntitiesByParams("disposicion", ["planificacion" => $comisionSiguiente->planificacion]);
        foreach($disposiciones as $disposicion) {
          $curso = new Curso_();
          $curso->horas_catedra = $disposicion->horas_catedra;
          $curso->setFk("comision", $comisionSiguiente);
          $curso->setFk("disposicion", $disposicion);
          $modifyQueries->insertSql($curso);
          echo "-- Curso creado: " . $curso->getLabel() . "<br>";
        }
      }

      $modifyQueries->process();
      echo "-- Fin de procesamiento de comision " . $comisionSiguiente->id . "<br><br>";

      ValueTypesUtils::redirect("Comisión creada correctamente",5);
} catch (Exception $exception){
  ValueTypesUtils::redirect($exception->getMessage(), 5);
}
