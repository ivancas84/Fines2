<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/AlumnoDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PersonaDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/CalendarioDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/PlanDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/CalificacionDAO.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Dao/DisposicionDAO.php');



add_submenu_page(
    null, 
    'Administrar Persona 2',
    'Administrar Persona 2', 
    'edit_posts', 
    'fines-plugin-ap2', 
    'ap2_administrar_persona_page'
  );

function ap2_administrar_persona_page() {

    $message = !empty($_GET['message']) ? $_GET['message'] : null;
    if($message) echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";

    $persona_id = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;





    //***** Campos de persona *****/
    $persona = (empty($persona_id)) ? null : PersonaDAO::personaById($persona_id);

    if(empty($persona)){
        $persona = array();
        foreach(PersonaDAO::getFields() as $field) $persona[$field] = null;
    }

    include plugin_dir_path(__FILE__) . 'ap2_persona_admin_form.html';






    //***** Campos de alumno *****/

    if(empty($persona))
        return;

    $estados_inscripcion= AlumnoDAO::estados_inscripcion();

    $planes = PlanDAO::planes();

    $alumno = AlumnoDAO::alumnoByPersona($persona['persona_id']);

    if(empty($alumno)){
        $alumno = array();
        foreach(AlumnoDAO::getFields() as $field) $alumno[$field] = null;
    }

    include plugin_dir_path(__FILE__) . 'ap2_alumno_admin_form.html';





    //***** CALIFICACIONES *****/
    if(empty($alumno))
        return;



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

