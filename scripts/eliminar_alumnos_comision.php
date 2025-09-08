<?php

//Eliminar todas las calificaciones del curso

require_once '../fines-config.php';

use Fines2\AlumnoComision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

try {
    $comision_id = $_GET["comision_id"];
    $db = \App\Context::getFinesDb();
    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
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