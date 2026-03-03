<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');
        require_once plugin_dir_path(__FILE__) . 'ppc3_functions.php';

use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
use Fines2\Calificacion;
use Fines2\Calificacion_;
use Fines2\CalificacionDAO;
use Fines2\PersonaDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_action('admin_post_ppc3_process', 'ppc3_process_handle');

function ppc3_process_handle() {
    $curso_id = wp_initialize_handle("fines-plugin-ppc3", "ppc3_process", "curso_id");

    $db = \App\Context::getFinesDb();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Curso_ */ $curso = $dataProvider->fetchEntityByParams("curso", ["id" => $curso_id]);
    if(empty($curso)) throw new Exception("No se ha encontrado el curso");
 
    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc3_form.html';
        return;
    }

    $observaciones = $_POST["observaciones"];
    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = ValueTypesUtils::excelParse($rawData);
   
    echo "<h2>Cantidad de calificaciones a procesar ". count($result) . "</h2>";
    foreach($result as $data) {
        try {
            $modifyQueries = \App\Context::getFinesDb()->CreateModifyQueries();
            try {
                switch($format) {
                    case "PF":
                        $data = ppc3_parse_pf($data);
                        break;
                    case "PF2":
                        $data = ppc3_parse_pf2($data);
                        break;
                    case "XLSX":
                        $data = ppc3_parse_xlsx($data);
                        break;
                    default:
                        throw new Exception("Formato no reconocido");
                }
            } catch(Exception $ex){
                continue;
            }

            /** @var Persona_ */ $persona = PersonaDAO::createAndPersist($modifyQueries, $data);

            /** @var Alumno_ */ $alumno = AlumnoDAO::createAndPersist($modifyQueries, $persona->id, $curso->comision_->planificacion_->plan); 

           /** @var AlumnoComision_ */ $alumnoComision = AlumnoComisionDAO::createAndPersist($modifyQueries, $alumno->id, $curso->comision_->id, "Importado desde planilla de calificaciones");

            /** @var Calificacion_ */ $calificacion = CalificacionDAO::createAndPersist($modifyQueries, $data["nota"], $alumno->id, $curso->disposicion, $curso->id, $observaciones);

            $modifyQueries->process();
            wp_redirect_handle("fines-plugin-ppc3", "curso_id", $curso->id, "Registro realizado");  
        } catch (Exception $e) {
            wp_redirect_handle("fines-plugin-ppc3", "curso_id", $curso->id, $e->getMessage());  
        }
    }

 
}