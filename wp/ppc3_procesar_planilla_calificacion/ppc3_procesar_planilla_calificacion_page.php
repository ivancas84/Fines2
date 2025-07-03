<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Calificacion_;
use Fines2\Curso_;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
use Fines2\CalificacionDAO;
use Fines2\PersonaDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DbMy;

add_submenu_page(
    null, 
    'Procesar Planilla Calificación',
    'Procesar Planilla Calificación', 
    'edit_posts', 
    'fines-plugin-ppc3', 
    'ppc3_procesar_planilla_calificacion_page'
  );

  
function ppc3_procesar_planilla_calificacion_page() {
    wp_page_message();

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Curso_ */ $curso = $dataProvider->fetchEntityByParams("\Fines2\Curso_", ["id" => $_GET['curso_id']]);
    if(empty($curso)) throw new Exception("No se ha encontrado el curso");
 
    echo "<h1>Cargar calificaciones en curso " . $curso->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc3_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = Tools::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de calificaciones a procesar ". count($result) . "</h2>";

    $i = 0;
    $existenDatos = false;

    foreach($result as $data) {
        try {
            $i++;
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            echo "<strong>Calificación: " . $i . ";</strong><br>";
            if($format == "pf"){
                echo " - " . $data["Apellido, Nombre DNI"]; 
                $data = PfUtils::parseRowCalificacionPF($data);
            } 


            /** @var Persona_ */ $persona = PersonaDAO::createAndPersist($modifyQueries, $data);

            /** @var Alumno_ */ $alumno = AlumnoDAO::createAndPersist($modifyQueries, $persona->id, $curso->comision_->planificacion_->plan); 

           /** @var AlumnoComision_ */ $alumnoComision = AlumnoComisionDAO::createAndPersist($modifyQueries, $alumno->id, $curso->comision_->id, "Importado desde planilla de calificaciones");

            /** @var Calificacion_ */ $calificacion = CalificacionDAO::createAndPersist($modifyQueries, $data["nota"], $alumno->id, $curso->disposicion, $curso->id);

            echo $modifyQueries->htmlDetail();
            echo $persona->htmlChangeLog();
            echo $alumno->htmlChangeLog();
            echo $alumnoComision->htmlChangeLog();
            echo $calificacion->htmlChangeLog();
            
            if(!empty($modifyQueries->detail)){
                $existenDatos = true;
            }
        } catch (Exception $e) {
            echo "- " . $e->getMessage() . "<br><br>";
        }
    }

    if($existenDatos){
        include plugin_dir_path(__FILE__) . 'ppc3_form_process.html';
    } else {
        echo "No existen datos para registrar";
    }
      
}
  