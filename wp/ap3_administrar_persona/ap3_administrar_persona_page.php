<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/db-config.php';

add_submenu_page(
    null, 
    'Administrar Persona 3',
    'Administrar Persona 3', 
    'edit_posts', 
    'fines-plugin-ap3', 
    'ap3_administrar_persona_page'
  );

use \Fines2\Persona_;
use \Fines2\Calificacion_;
use \Fines2\CalificacionDAO;
use \Fines2\Alumno_;
use \Fines2\AlumnoDAO;
use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;

function ap3_administrar_persona_page() {

    wp_page_message();
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;


    /** @var Persona_ */ $persona = new Persona_();
    if(!empty($persona_id)) $persona->initById($persona_id);

    include plugin_dir_path(__FILE__) . 'ap3_persona_form_html.php';

    if($persona->_status < 0)
        return;

    
    //***** Campos de alumno *****/
    $dataProvider = DbMy::getInstance()->CreateDataProvider();

    $estados_inscripcion = $dataProvider->fetchAllColumnByParams("alumno", "estado_inscripcion", [], ["estado_inscripcion"=>"ASC"]);
    $planes = $dataProvider->fetchAllEntitiesByParams("plan");

    $alumno = new Alumno_();
    $alumno->initByUnique(["persona" => $persona->id]);

    include plugin_dir_path(__FILE__) . 'ap3_alumno_form_html.php';

    if($alumno->_status < 0)
        return;


    //***** CALIFICACIONES *****/
    $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
    AlumnoDAO::reestructurarCalificacionesByAlumno($modifyQueries, $alumno);
    $modifyQueries->process();

    $tramo = $alumno->getTramoShort();

    if(!empty($alumno->plan)){
        $calificaciones = CalificacionDAO::calificacionesByAlumnoPlanTramo($alumno->id, $alumno->plan, $tramo);
        if ($calificaciones) {
            $titulo_calificaciones = "Aprobadas del mismo plan";
            include plugin_dir_path(__FILE__) . 'ap3_calificaciones_table_html.php';
        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
        
        
        $calificaciones = CalificacionDAO::calificacionesAprobadasByAlumnoNotInPlan($alumno->id, $alumno->plan);
        if ($calificaciones) {
            $titulo_calificaciones = "Aprobadas de otro plan";
            include plugin_dir_path(__FILE__) . 'ap3_calificaciones_table_html.php';
        } else {
             echo "<p>No se encontraron calificaciones adicionales para este alumno.</p>";
        }    
    }

}

