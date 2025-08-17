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

    
$db = DbMy::getInstance();
/** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

$comisionesAutorizadasSemestreSin32 = ComisionDAO::comisionesAutorizadasSin32ByCalendario(CALENDARIO_ID_ANTERIOR);

foreach($comisionesAutorizadasSemestreSin32 as $comision) {
  try {
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    echo "Procesando Comisión: " . $comision->getLabel() . " (" . $comision->id . ")<br>";
    
    $tramoSiguiente = $comision->planificacion_?->getTramoSiguiente();
      /** @var Planificacion_ */ $nuevaPlanificacion = DbMy::getInstance()->CreateDataProvider()->fetchEntityByParams("planificacion", [
            "plan" => $comision->planificacion_->plan,
            "anio" => $tramoSiguiente["anio"],
            "semestre" => $tramoSiguiente["semestre"]
        ]);

    if(!empty($comision->comision_siguiente)) {
      /** @var Comision_ */ $comisionSiguiente = $dataProvider->fetchEntityByParams("comision", ["id" => $comision->comision_siguiente]);
      echo "-- Ya tiene comisión siguiente asignada " . $comisionSiguiente->id . "<br>";
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
        $modifyQueries->buildInsertSql($curso);
        echo "-- Curso creado: " . $curso->getLabel() . "<br>";
      }
    }

    $modifyQueries->process();
    echo "-- Fin de procesamiento de comision " . $comisionSiguiente->id . "<br><br>";

  } catch (Exception $ex) {
    echo "-- Error: " . $ex->getMessage() . "<br>";
  }
}

