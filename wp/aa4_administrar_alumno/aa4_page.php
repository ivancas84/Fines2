<?php
session_start();

use App\Context;
use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Calificacion_;
use Fines2\Model\Persona_;
use ProgramaFines\DataAccess\AlumnoNoExisteException;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganize\Sql\Db;

add_submenu_page(
    null, 
    'Administrar Alumno',
    'Administrar Alumno', 
    'edit_posts', 
    FINES_PLUGIN.'-aa4', 
    'ap4_page'
);

function ap4_page() {

    $pf_session = $_SESSION["PHPSESS"] ?? null;
    wp_page_message();
    $persona = ap4_init_Persona($pf_session);
    if($persona->_status < 0) return;

    
    if(!empty($pf_session)){
        ap4_init_AlumnoPf($persona, $pf_session);
    }


    $alumno = ap4_init_Alumno($persona);
    if($alumno->_status < 0) return;
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
    $estados = $dataProvider->fetchAllColumnByParams("alumno_comision", "estado", [], ["estado"=>"ASC"]);

    ap4_init_Comisiones($alumno, $estados);
    ap4_init_comision_add($alumno, $estados);

    ap4_init_Calificaciones($alumno, $persona);
    aa4_init_Detalles($persona);

}


    function ap4_init_Persona(?string $pf_session): Persona_{
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
        /** @var Alumno_ */ $alumno = $db->createEntityByUnique("alumno", ["persona" => $persona->id]);

        include plugin_dir_path(__FILE__) . 'aa4_alumno_form_html.php';
        return $alumno;
    }

    function ap4_init_AlumnoPf(Persona_ $persona, string $pf_session): void{
        $pfdao = new PfDAO($pf_session);
        try {
            $data = $pfdao->openFormModificarAlumno($persona->numero_documento);
            /** @var Persona_ */ $personaPf = clone $persona;
            $personaPf->ssetNotNullFromPF($data);
            $diferencias = $persona->compare($personaPf);
            if(empty($diferencias)){
                echo "<p>El alumno existe en programafines, no se encontraron diferencias en datos relevantes<p>";
            } else {
                echo "<p>El alumno existe en programafines, se encontraron las siguientes diferencias en datos relevantes<p>";
                echo "<pre>";
                print_r($diferencias);
                echo "</pre>";
            }

        } catch (AlumnoNoExisteException $ex){
            echo "<p>El alumno no existe en programafines</p>";
        }
    }


function ap4_init_comisiones(Alumno_ $alumno, array $estados){
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
    
    /** @var AlumnoComision_[] */ $alumno_comisiones = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["alumno" => $alumno->id], ["id" => "DESC"]);

    if ($alumno_comisiones) {
        //$alumno, $estados
        include plugin_dir_path(__FILE__) . 'aa4_comisiones_table_html.php';
    } else {
        echo "<p>No hay comisiones asignadas.</p>";
    }
}


function ap4_init_comision_add(Alumno_ $alumno, array $estados) {
    include plugin_dir_path(__FILE__) . 'aa4_comision_add_form_html.php';
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

function aa4_init_Detalles(Persona_ $persona){
    
    $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();

    //***** DETALLE PERSONA *****/
    /** @var DetallePersona_ */ $detalles = $dataProvider->fetchAllEntitiesByParams("detalle_persona", ["persona"=>$persona->id]);
    if ($detalles) {
        include plugin_dir_path(__FILE__) . 'aa4_detalles_table_html.php';
    } else {
            echo "<p>No hay detalles.</p>";
    }
}