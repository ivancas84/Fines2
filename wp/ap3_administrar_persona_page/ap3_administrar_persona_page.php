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
use \Fines2\Alumno_;
use Fines2\AlumnoDAO;
use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Sql\Entity;

function ap3_administrar_persona_page() {

    wp_page_message();
    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;

    /** @var Persona_ */ $persona = (empty($persona_id)) ? new Persona_(): DbMy::getInstance()->CreateDataProvider()->fetchEntityByParams("persona", ["id" =>$persona_id]);

    include plugin_dir_path(__FILE__) . 'ap3_persona_admin_form.html';

    if($persona->status < 0)
        return;

    
    //***** Campos de alumno *****/
    $dataProvider = DbMy::getInstance()->CreateDataProvider();


    $estados_inscripcion = $dataProvider->fetchAllColumnByParams("alumno", "estado_inscripcion", [], ["estado_inscripcion"=>"ASC"]);
    $planes = $dataProvider->fetchAllEntitiesByParams("plan");

    $alumno = new Alumno_();
    $alumno->initByUnique(["persona" => $persona->id]);

    include plugin_dir_path(__FILE__) . 'ap3_alumno_admin_form.html';

    if($alumno->_status < 0)
        return;


    //***** CALIFICACIONES *****/
    AlumnoDAO::reestructurarCalificacionesByAlumno($alumno);

    $tramo = AlumnoDAO::tramo($alumno);

    if(!empty($alumno["plan"])){
        $calificaciones = CalificacionDAO::calificacionesByAlumnoPlanTramo($alumno["id"], $alumno["plan"], $tramo);
        if ($calificaciones) {
            include plugin_dir_path(__FILE__) . 'ap2_calificaciones_table_update_form.html';
        } else {
            echo "<p>No se encontraron calificaciones para este alumno.</p>";
        }
        
        
        $calificaciones_ = CalificacionDAO::calificacionesAprobadasByAlumnoNotInPlan($alumno["id"], $alumno["plan"]);
        if ($calificaciones_) {
            include plugin_dir_path(__FILE__) . 'ap2_calificaciones_aux_table.html';
        } else {
            echo "<p>No se encontraron calificaciones adicionales para este alumno.</p>";
        }    
    }

}

