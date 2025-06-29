<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Calificacion_;
use Fines2\CalificacionDAO;
use Fines2\Curso_;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\DesignacionDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

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
        include plugin_dir_path(__FILE__) . 'ppc_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $format = $_POST['format'];
    $result = Tools::excelParse($rawData);
    echo "<h2>Cantidad de alumnos a procesar ". count($result) . "</h2>";

    $alumnosComision = $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $curso->comision]);
    $alumnosComision = ValueTypesUtils::dictOfObjByPropertyNames($alumnosComision, "numero_documento");
    $dnisComision = array_keys($alumnosComision);

    $calificaciones = CalificacionDAO::calificacionesAprobadasByDisposicionAndDnis($curso->disposicion, $dnisComision);
    $calificaciones = ValueTypesUtils::dictOfObjByPropertyNames($calificaciones, "numero_documento");

    $i = 0;
    foreach($result as $row) {
        try {
            $i++;
            echo "<br><br>Calificación: " . $i . ";<br>";
            if($format == "pf"){
                echo " - " . $row["Apellido, Nombre DNI"]; 
                $row = PfUtils::parseRowCalificacionPF($row);

                /** @var Persona_ */ $persona = Persona_::createAndPersistByUnique("\\Fines2\\Persona_", $row, true);

                /** @var Alumno_ */ $alumno = Alumno_::createAndPersistByUnique("\\Fines2\\Alumno_", ["persona"=>$persona->id, "plan"=>$curso->comision_->planificacion_->plan]);
            
                AlumnoComision_::createAndInsertByUnique("\\Fines2\\AlumnoComision_", [
                    "alumno"=>$alumno->id, 
                    "comision"=>$curso->comision, 
                    "estado"=>($alumno->_status < 0) ? "Ingresante" : "Incorporado", 
                    "observaciones" => "Importado desde planilla de calificación"]);

                Calificacion_::createAndPersistAprobadaByUnique($alumno->id, $curso->disposicion, $curso->id, $data["calificacion"]);
            }

        } catch (Exception $e) {
            echo "- " . $e->getMessage() . "<br><br>";
        }
    }
      
}
  