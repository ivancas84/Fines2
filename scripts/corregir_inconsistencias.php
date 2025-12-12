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
use Fines2\Calificacion_;
use Fines2\CalificacionDAO;
use Fines2\Comision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

/** @var DataProvider */ $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
/** @var Comision_[] */ $comisionesSemestre = $dataProvider->fetchAllEntitiesByParams("comision", ["calendario"=>CALENDARIO_ID_ANTERIOR]);


foreach($comisionesSemestre as $comision){
    /** @var ModifyQueries */ $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();
    
    echo "<h2>Procesando comisión " . $comision->pfid . " " . $comision->planificacion_->getTramo() . "</h2>";
    echo "<p>Plan de la comisión: " . $comision->planificacion_->plan_->getLabel() . "</p>";

    /** @var AlumnoComision_[] */ $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=>$comision->id]);
    if($comision->comision_siguiente != null){
        echo "<p>Comisión siguiente: " . $comision->comision_siguiente_->pfid . "</p>";
        $alumnosComisionSiguiente = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision"=>$comision->id]);
        $idsAlumnosComisionSiguiente = ValueTypesUtils::arrayOfName($alumnosComisionSiguiente, "alumno");
    } else {
        echo "<p>No tiene comisión siguiente.</p>";
    }
    echo "<p>Total de alumnos en la comisión: " . count($alumnosComision) . "</p>";
 
    $control_duplicados = [];

    foreach($alumnosComision as $alumnoComision){

        
        if(array_key_exists($alumnoComision->alumno_->persona, $control_duplicados)){
            echo "<p style='color:red;'>Alumno duplicado " . $alumnoComision->alumno_->persona_->getLabel() . "</p>";
            echo "<pre>".print_r($alumnoComision->toArray(), true)."</pre>";
            $modifyQueries->buildDeleteSqlById("alumno_comision", $alumnoComision->id);
            echo "<p style='color:blue;'>Consulta " . $modifyQueries->getCounter() . ".</p>";
            echo "<pre>".print_r($control_duplicados[$alumnoComision->alumno_->persona]->toArray(), true)."</pre>";
            $modifyQueries->buildDeleteSqlById("alumno_comision", $control_duplicados[$alumnoComision->alumno_->persona]->id);
            echo "<p style='color:blue;'>Consulta " . $modifyQueries->getCounter() . ".</p>";
            continue;
        } else {
            $control_duplicados[$alumnoComision->alumno_->persona] = $alumnoComision;
        }

        echo "<h3>_ Procesando alumno " . $alumnoComision->alumno_->persona_->getLabel() . "</h3>";
        

        if($alumnoComision->alumno_->plan != $comision->planificacion_->plan){
            echo "<p style='color:red;'>Plan diferente alumno " . ($alumnoComision->alumno_?->plan_?->getLabel() ?? "Sin plan") . "</p>";
            $modifyQueries->buildUpdateKeyValueSqlById("alumno", "plan", $comision->planificacion_->plan, $alumnoComision->alumno);
            echo "<p style='color:blue;'>Consulta " . $modifyQueries->getCounter() . ".</p>";             
        }

        /** @var Calificacion_[] */ $calificacionesAprobadasPlan = CalificacionDAO::calificacionesAprobadasAlumnoPlan($alumnoComision->alumno, $comision->planificacion_->plan);
    
        echo "<p>Total de calificaciones aprobadas en el plan de la comisión: " . count($calificacionesAprobadasPlan) . "</p>";
    
        /** @var Calificacion_[] */ $calificacionesAprobadasNotInPlan = CalificacionDAO::calificacionesAprobadasByAlumnoNotInPlan($alumnoComision->alumno, $comision->planificacion_->plan);

        echo "<p>Total de calificaciones aprobadas fuera del plan de la comisión: " . count($calificacionesAprobadasNotInPlan) . "</p>";
    
        /** @var Calificacion_[] */ $calificacionesAprobadasPlanificacion = CalificacionDAO::CalificacionesAprobadasAlumnoPlanificacion($alumnoComision->alumno, $comision->planificacion);
    
        echo "<p>Total de calificaciones aprobadas en la planificacion de la comisión: " . count($calificacionesAprobadasPlanificacion) . "</p>";

        if($calificacionesAprobadasPlanificacion < 3 ) {
            echo "<p>El alumno tiene menos de 3 calificaciones aprobadas en la planificacion de la comisión.</p>";
        } else {
            if($comision->comision_siguiente != null) {
                if(in_array($alumnoComision->alumno, $idsAlumnosComisionSiguiente)) {
                    echo "<p>El alumno ya está en la comisión siguiente.</p>";
                } else {
                    echo "<p style='color:green;'>El alumno puede ser promovido a la comisión siguiente.</p>";
                    $alumnoComisionSiguiente = new AlumnoComision_();
                    $alumnoComisionSiguiente->alumno = $alumnoComision->alumno;
                    $alumnoComisionSiguiente->comision = $comision->comision_siguiente;
                    $alumnoComisionSiguiente->estado = "Regular";
                    $alumnoComisionSiguiente->activo = true;
                    $alumnoComisionSiguiente->observaciones = "Promovido automáticamente desde la comisión " . $comision->getLabel() . " por tener al menos 3 calificaciones aprobadas en la planificación.";
                    $modifyQueries->buildInsertSql($alumnoComisionSiguiente);
                    echo "<p style='color:blue;'>Consulta " . $modifyQueries->getCounter() . ".</p>"; 
                }
            }
        }
            
    }

    echo "<pre>" . $modifyQueries->getSqlPreview() . "</pre><br /><br /><br />";

}

