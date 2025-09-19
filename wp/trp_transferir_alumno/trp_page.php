<?php

use App\Context;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\PersonaDAO;
use Fines2\Alumno_;
use Fines2\Calificacion_;
use Fines2\DetallePersona_;
use Fines2\Designacion_;
use SqlOrganize\Sql\Db;


use Fines2\CalificacionDAO;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

require_once $_SERVER['DOCUMENT_ROOT'] . '/fines-config.php';

add_submenu_page(
    null, 
    'Transferir Persona',
    'Transferir Persona', 
    'edit_posts', 
    'fines-plugin-ta', 
    'trp_page'
);


function trp_page() {
    wp_page_message();

    include plugin_dir_path(__FILE__) . 'trp_form_html.php';

    if (!isset($_GET['submit']) || empty($_GET['dni_origen']) || empty($_GET['dni_origen'])) {
        return;
        
    $dni_origen = $_GET['dni_origen'];
    $dni_destino = $_GET['dni_destino'];

    /** @var Db */ $db = Context::getFinesDb();
    $dataProvider = $db->CreateDataProvider();
    /** @var Persona_ */ $persona_origen = $dataProvider->fetchEntityByParams("persona", ["numero_documento" => $dni_origen]);
    /** @var Persona_ */ $persona_destino = $dataProvider->fetchEntityByParams("persona", ["numero_documento" => $dni_destino]);

    if(!$persona_origen){
        echo "<p>No se encontró una persona con el dni $dni_origen</p>";
        return;
    }

    if(!$persona_destino){
        echo "<p>No se encontró una persona con el dni $dni_destino</p>";
        return;
    }

    /** @var ModifyQueries */ $modifyQueries = $db->createModifyQueries();
    /** @var DetallePersona_[] */ $dpos = $dataProvider->fetchEntitiesByParams("detalle_persona", ["persona" => $persona_origen->id]);
    foreach($dpos as $dp){
        $dp->persona = $persona_destino->id;
        $modifyQueries->buildUpdateKeySqlById($dp, "persona");
    }

    /** @var Designacion_[] */ $dess = $dataProvider->fetchEntitiesByParams("designacion", ["persona" => $persona_origen->id]);
    foreach($dess as $des){
        $des->persona = $persona_destino->id;
        $modifyQueries->buildUpdateKeySqlById($des, "persona");
    }


    /** @var ?Alumno_ */ $alumno_destino = $dataProvider->fetchEntityByParams("alumno", ["persona" => $persona_origen->id]);

    if(!empty($alumno_destino)){

        /** @var ?Alumno_ */ $alumno_origen = $dataProvider->fetchEntityByParams("alumno", ["persona" => $persona_origen->id]);

        if(!empty($alumno_origen)){ 
            echo "<p>La persona origen y destino tienen alumnos asociados. Se muestran a continuación los datos del alumno origen por si se desea modificar de forma manual</p>";
            echo "<pre>";
            print_r($alumno_origen->toArray());
            echo "</pre>";
            echo "<p>PLAN " . ($alumno->plan_?->getLabel() ?? "No definido") . "</p>";
            echo "<p>RESOLUCIÓN INSCRIPCION " . ($alumno->resolucion_inscripcion_?->getLabel() ?? "No definido") . "</p>";
     
            /** @var Calificacion_[] */ $calificaciones_origen = CalificacionDAO::calificacionesAprobadasByAlumno();
            foreach($calificaciones_origen as $calificacion){
                $calificacion->alumno = $alumno_destino->id;
                $calificacion->updateField("alumno");
            }

            /** @var AlumnoComision_[] */ $alumno_comision_origen = $dataProvider->fetchEntitiesByParams("alumno_comision", ["alumno" => $alumno_origen->id]);
            foreach($alumno_comision_origen as $ac){
                $ac->alumno = $alumno_destino->id;
                $ac->updateField("alumno");
            }
        } else {
            echo "<p>La persona destino no tiene un alumno asociado. Se cambia alumno.persona.</p>";

            $alumno_origen->persona = $persona_destino->id;
            $alumno_origen->updateField("persona");
        }



        return;
        
    } else if(empty($alumno_origen)){
        echo "<p>La persona origen no tiene un alumno asociado.</p>";
        return;
    }


    if(!$alumno){
        echo "<p>No se encontró un alumno asociado a ese id.</p>";
        die();
    }

    /** @var ?AlumnoComision_ */ $alumno_comision = AlumnoComisionDAO::ultimaComisionAlumno($alumno->id);
    if(empty($alumno_comision)){
        $alumno_comision = new AlumnoComision_();
    }

    $notas = $alumno_comision->comision_?->getLabel() ?? "Sin comision activa"; 
    $anio = ValueTypesUtils::toOrdinalSpanish($alumno_comision->comision_?->planificacion_?->anio ?? "");
	$fecha = ValueTypesUtils::fechaActualDiaDeMesDeAnio();
    $presentado = "Quien corresponda";
    $observaciones = "";
	
    include plugin_dir_path(__FILE__) . 'ccc_constancia_certificado_completo_page_html.php';
}


