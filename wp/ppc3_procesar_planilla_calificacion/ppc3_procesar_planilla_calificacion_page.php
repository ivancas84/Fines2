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

    $i = 0;
    foreach($result as $row) {
        try {
            $i++;
            echo "<br><br>Calificación: " . $i . ";<br>";
            if($format == "pf"){
                echo " - " . $row["Apellido, Nombre DNI"]; 
                $data = PfUtils::parseRowCalificacionPF($row);
            } 

            /** @var Persona_ */ $persona = Persona_::createByUnique("Fines2\Persona_", $data);
            if ($persona->_status === 0){
                if(!Persona_::nombreParecido($persona->toArray(), $data))
                    throw new Exception("El nombre registrado de la persona es diferente " . $persona->getLabel());
                $modifyQueries->buildUpdateSql($persona);
            }
            else if ($persona->_status < 0)
                $modifyQueries->buildInsertSql($persona);

            /** @var Alumno_ */ $alumno = Alumno_::createByUnique("Fines2\Alumno_", $data);
            $alumno->set("persona", $persona->id);
            $alumno->set("plan", $comision->planificacion_->plan);
            $modifyQueries->buildPersistSqlByStatus($alumno);

            /** @var AlumnoComision_ */ $alumnoComision = AlumnoComision_::createByUnique("Fines2\Alumno_", $data);
            $alumnoComision->set("alumno", $alumno->id);
            $alumnoComision->set("comision", $curso->comision);

            if ($alumnoComision->_status < 0){
                $alumnoComision->set("estado", ($modifyQueries->getDetailAction("alumno", $alumno->id) == "insert") ? "Ingresante" : "Incorporado");
                $alumnoComision->set("observaciones", "Importado desde lista de alumnos");
                $modifyQueries->buildInsertSql($persona);
            }

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
  