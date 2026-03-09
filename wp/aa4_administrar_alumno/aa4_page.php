<?php

use App\Context;
use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Calificacion_;
use Fines2\Model\Persona_;
use SqlOrganize\Sql\Db;

add_submenu_page(
    FINES_PLUGIN, 
    'Administrar Alumno',
    'Administrar Alumno', 
    'edit_posts', 
    FINES_PLUGIN.'-aa4', 
    'ap4_page'
);

function ap4_page() {

    wp_page_message();
    $persona = ap4_init_Persona();
    if($persona->_status < 0) return;

    $alumno = ap4_init_Alumno($persona);
    if($alumno->_status < 0) return;

    
    ap4_init_Comisiones($alumno, $persona);
    ap4_init_Calificaciones($alumno, $persona);
    ap4_init_Detalles($persona);

}


function ap4_init_Persona(): Persona_{
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;

    /** @var Db */ $db = Context::getFinesDb();
    if(empty($persona_id)) $persona = $db->createEntity("persona");
    else $persona = $db->createEntityById("persona", $persona_id);

    include plugin_dir_path(__FILE__) . 'aa4_persona_form_html.php';

    return $persona;
}

function ap4_init_Alumno(Persona_ $persona): Alumno_{
    //***** Campos de alumno *****/
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();

    $estados_inscripcion = $dataProvider->fetchAllColumnByParams("alumno", "estado_inscripcion", [], ["estado_inscripcion"=>"ASC"]);
    $planes = $dataProvider->fetchAllEntitiesByParams("plan");

    /** @var Db */ $db = Context::getFinesDb();
    /** @var Alumno_ */ $alumno = $db->createEntityByUnique("persona", ["persona" => $persona->id]);

    include plugin_dir_path(__FILE__) . 'aa4_alumno_form_html.php';
    return $alumno;
}

function ap4_init_comisiones(Alumno_ $alumno, Persona_ $persona){
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
    $estados = $dataProvider->fetchAllColumnByParams("alumno_comision", "estado", [], ["estado"=>"ASC"]);
    
    /** @var AlumnoComision_[] */ $alumno_comisiones = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["alumno" => $alumno->id], ["id" => "DESC"]);

    if ($alumno_comisiones) {
        include plugin_dir_path(__FILE__) . 'aa4_comisiones_table_html.php';
    } else {
        echo "<p>No hay comisiones asignadas.</p>";
    }
}
function ap4_init_Calificaciones(Alumno_ $alumno, Persona_ $persona = null){
    //***** CALIFICACIONES *****/
    $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();
    AlumnoDAO::reestructurarCalificacionesByAlumno($modifyQueries, $alumno);
    $modifyQueries->process();

    $tramo = $alumno->getTramoIngresoShort();

    if(!empty($alumno->plan)){
        /** @var Calificacion_[] */ $calificaciones = CalificacionDAO::calificacionesByAlumnoPlanTramo($alumno->id, $alumno->plan, $tramo);
        if ($calificaciones) {
            $titulo_calificaciones = " del plan";
            include plugin_dir_path(__FILE__) . 'aa4_calificaciones_table_html.php';
        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
        
        
        /** @var Calificacion_[] */$calificaciones = CalificacionDAO::calificacionesAprobadasByAlumnoNotInPlan($alumno->id, $alumno->plan);
        if ($calificaciones) {
            $titulo_calificaciones = " de otro plan";
            include plugin_dir_path(__FILE__) . 'aa4_calificaciones_table_html.php';
        } else {
             echo "<p>No se encontraron calificaciones adicionales para este alumno.</p>";
        }    
    }
}

function ap4_init_Detalles(Persona_ $persona){
    
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();

    //***** DETALLE PERSONA *****/
    /** @var DetallePersona_ */ $detalles = $dataProvider->fetchAllEntitiesByParams("detalle_persona", ["persona"=>$persona->id]);
    if ($detalles) {
        include plugin_dir_path(__FILE__) . 'ap4_detalles_table_html.php';
    } else {
            echo "<p>No hay detalles.</p>";
    }
}