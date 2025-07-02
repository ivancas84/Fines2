<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Calificacion_;
use Fines2\Curso_;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
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
    
    /** @var Curso_ */ $curso = $dataProvider->fetchEntityByParams("curso", ["id" => $_GET['curso_id']]);
    if(empty($curso)) throw new Exception("No se ha encontrado el curso");
 
    echo "<h1>Cargar calificaciones en curso " . $curso->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data']) || empty($_POST['format'])) {
        include plugin_dir_path(__FILE__) . 'ppc3_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = Tools::excelParse($rawData);
    echo "<h2>Cantidad de calificaciones a procesar ". count($result) . "</h2>";

    $i = 0;
    foreach($result as $row) {
        try {
            $i++;
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            echo "<strong>Calificación: " . $i . ";</strong><br>";
            if($format == "pf"){
                echo " - " . $row["Apellido, Nombre DNI"]; 
                $data = PfUtils::parseRowCalificacionPF($row);
            } 

            /** @var Persona_ */ $persona = PersonaDAO::createAndPersist($modifyQueries, $data);

            /** @var Alumno_ */ $alumno = AlumnoDAO::createAndPersistByPersonaAndPlan($modifyQueries, $persona->id, $comision->planificacion_->plan); 

           AlumnoComisionDAO::createAndPersist($modifyQueries, $alumno->id, $comision->id, "Importado desde planilla de calificaciones");

            /** @var Calificacion_ */ $calificacion = $dataProvider->fetchEntityByParams("calificacion", ["alumno" => $alumno->id, "disposicion" => $curso->disposicion]);
            if(empty($calificacion)){
                $calificacion = new Calificacion_();
            } else {
                $calificacion->_status = 1;
            }
            $calificacion->set("alumno", $idAlumno);
            $calificacion->set("disposicion", $idDisposicion);
            $calificacion->set("curso", $idCurso);
            $calificacion->setNotaAprobada($nota);
            $modifyQueries->buildPersistSqlByStatus($calificacion);

        } catch (Exception $e) {
            echo "- " . $e->getMessage() . "<br><br>";
        }
    }
      
}
  