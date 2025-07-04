<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
use Fines2\CalificacionDAO;
use Fines2\PersonaDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DbMy;

add_action('admin_post_ppc3_process', 'ppc3_process_handle');

function ppc3_process_handle() {
    $curso_id = wp_initialize_handle("fines-plugin-ppc3", "ppc3_process", "curso_id");

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Curso_ */ $curso = $dataProvider->fetchEntityByParams("curso", ["id" => $curso_id]);
    if(empty($curso)) throw new Exception("No se ha encontrado el curso");
 
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc3_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = Tools::excelParse($rawData);
   
    foreach($result as $row) {
        try {
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            if($format == "pf"){
                try {
                    $data = PfUtils::parseRowCalificacionPF($row);
                } catch(Exception $ex){
                    continue;
                }
            } 

            /** @var Persona_ */ $persona = PersonaDAO::createAndPersist($modifyQueries, $data);

            /** @var Alumno_ */ $alumno = AlumnoDAO::createAndPersist($modifyQueries, $persona->id, $comision->planificacion_->plan); 

           /** @var AlumnoComision_ */ $alumnoComision = AlumnoComisionDAO::createAndPersist($modifyQueries, $alumno->id, $comision->id, "Importado desde planilla de calificaciones");

            /** @var Calificacion_ */ $calificacion = CalificacionDAO::createAndPersist($modifyQueries, $nota, $alumno->id, $curso->disposicion, $curso->id);

            $modifyQueries->process();
            wp_redirect_handle("fines-plugin-ppc3", "curso_id", $curso->id, "Registro realizado");  
        } catch (Exception $e) {
            wp_redirect_handle("fines-plugin-ppc3", "curso_id", $curso->id, $e->getMessage());  
        }
    }

    


}