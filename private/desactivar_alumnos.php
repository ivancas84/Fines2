<?php

//Definir sql para transferir alumnos aprobados

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use Fines2\Model\Comision_;
use App\Context;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;

try {
    
    /** @var Db */ $dbf = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider =  $dbf->CreateDataProvider();
    /** @var Comision_[] */ $comisiones = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario" => CALENDARIO_ID_ANTERIOR]);
    
    echo "<h2>Comisiones a procesar: " . count($comisiones) . "</h2>";
    
    foreach($comisiones as $comision) {
        echo "<br><br><h2>Procesando " . $comision->pfid . "</h2>";

        /** @var AlumnoComision_[] */ $alumnosComision =  $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id]);

        $dnis = [];
        $cantidadActivos = 0;
        $cantidadDesactivos = 0;
        foreach($alumnosComision as $ac) {
            if (!$ac->activo)  $cantidadDesactivos++; else $cantidadActivos++;
        }
        echo "<p>Cantidad de alumnos activos " . $cantidadActivos."</p>";
        echo "<p>Cantidad de alumnos no activos " . $cantidadDesactivos."</p>";
    }
    foreach($comisiones as $comision) {
        /** @var ModifyQueries */ $modifyQueries = $dbf->CreateModifyQueries();
        /** @var AlumnoComision_[] */ $alumnosComision =  $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id]);

        echo "<br><br><h2>Procesando " . $comision->pfid . "</h2>";

        foreach($alumnosComision as $ac) {
            array_push($dnis, $ac->alumno_->persona_->numero_documento);
            echo "<h2>Procesando " . $ac->alumno_->persona_->getLabel() . "</h2>";
            if($ac->alumno_->plan != $comision->planificacion_->plan)
                echo "<p>El plan del alumno es distinto de la comisión</p>";
            $calificacionesAprobadas = CalificacionDAO::CalificacionesAprobadasAlumnoPlanificacion($ac->alumno, $comision->planificacion);
            echo "<p>Calificaciones aprobadas " . count($calificacionesAprobadas);
            if(count($calificacionesAprobadas) < 3 && $ac->activo){
                echo " - El alumno será desactivado";
                $modifyQueries->updateKeyValueSqlById("alumno_comision", "activo", false, $ac->id);
            } else if(count($calificacionesAprobadas) >= 3 && !$ac->activo){
                echo " - El alumno será activado";
                $modifyQueries->updateKeyValueSqlById("alumno_comision", "activo", true, $ac->id);
            }
            echo "</p><br><br>";

        }

        $modifyQueries->process();
    }   



    //$dataProvider = $db->CreateDataProvider();
    //$modifyQueries = $db->CreateModifyQueries();

    //echo "<p>Estado modificado correctamente, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}