<?php

require_once '../fines-config.php';


//Definir sql para transferir alumnos aprobados

//La ultima comision activa del alumno debe coincidir con el plan del alumno. Corregirlo e imprimir que alumno es para chequear.
//No debe haber dos alumnos con comisiones activas en el mismo periodo, imprimir y dar la opcion para que el usuario lo elimina manualmente.
//No debe haber alumnos sin genero definido.
//Controlar la cantidad de calificaciones aprobadas para un mismo tramo plan, no debe ser mayor a 5
//no debe duplicarse disposicion de calificacion aprobada de un alumno.
//no debe haber asignaturas desaprobadas en la base de datos.

use Fines2\AlumnoComision_;
use Fines2\Comision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;

/** @var DataProvider */ $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
/** @var Comision_[] */ $comisionesSemestre = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario"=>CALENDARIO_ID_ANTERIOR]);
/** @var ModifyQueries */ $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();

foreach($comisionesSemestre as $comision){
    
    echo "<h2>Procesando comisión " . $comision->pfid . "</h2>";
    echo "<p>Plan de la comisión: " . $comision->planificacion_->plan_->getLabel() . "</p>";

    /** @var AlumnoComision_[] */ $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=>$comision->id]);

    echo "<p>Total de alumnos en la comisión: " . count($alumnosComision) . "</p>";

    
    foreach($alumnosComision as $alumnoComision){
        echo "<h3>_ Procesando alumno " . $alumnoComision->alumno_->persona_->getLabel() . "</h3>";
        
        if($alumnoComision->alumno_->plan != $comision->planificacion_->plan){
            echo "<p style='color:red;'>Plan diferente  alumno " . $alumnoComision->alumno_->plan . "-" . $comision->planificacion_->plan . "</p>";
            echo "<p style='color:red;'>Plan diferente  alumno " . $alumnoComision->alumno_->plan_->getLabel() . "</p>";

            $modifyQueries->buildUpdateKeyValueSqlById("alumno", "plan", $comision->planificacion_->plan, $alumnoComision->alumno);
            echo "<pre>".$modifyQueries->getSqlPreview()."</pre>";
        }
    }


}