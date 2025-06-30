<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\Comision_;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, 
    'Cargar Alumnos Comisión',
    'Cargar Alumnos Comisión', 
    'edit_posts', 
    'fines-plugin-cac2', 
    'cac2_cargar_alumnos_comision_page'
);

function cac2_cargar_alumnos_comision_page() {
    wp_page_message();

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("comision", ["id" => $_GET['comision_id']]);
    if(empty($comision)) throw new Exception("No se ha encontrado la comision");
 
    echo "<h1>Cargar alumnos en comisión " . $comision->getLabel() . "</h1>";

    if (!isset($_POST['submit']) || empty($_POST['data'])) {
        include plugin_dir_path(__FILE__) . 'cac2_form.html';
        return;
    }

    $rawData = trim($_POST['data']);
    $alumnosData = ValueTypesUtils::excelParseIgnorePrefix($rawData);
    echo "<h2>Cantidad de alumnos a procesar ". count($alumnosData) . "</h2>";
    $dnisProcesados = [];
    
    $i = 0;

    foreach($alumnosData as $data){
        try {
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            $i++;
            echo "<br><br>Alumno: " . $i . ";<br>";
            $cuilDni = Persona_::cuilDni($data["dni_cuil"]);
            if(empty($cuilDni["dni"])){
                echo $data["apellidos"] . " " . $data["nombres"] . "<br>";
                throw new Exception("DNI vacío, no se procesará el alumno");
            }

            if(in_array($cuilDni["dni"], $dnisProcesados)){
                echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["dni"] . "<br>";
                throw new Exception("DNI ya procesado, no se procesará el alumno");
            }
            $dnisProcesados[] = $cuilDni["dni"];

            $data["numero_documento"] = $cuilDni["dni"];
            $data["cuil"] = $cuilDni["cuil"];

            echo $data["apellidos"] . " " . $data["nombres"] . " " . $data["numero_documento"] . "<br>";

            /** @var Persona_ */ $persona = Persona_::createAndPersistByUnique("Fines2\Persona_", $data, true);
            $data["persona"] = $persona->id;
            $data["plan"] = $comision->planificacion_->plan;
            $idAlumno = $modifyQueries->buildPersistSql_("alumno", $data);

            AlumnoComision_::imprimirComisionesByAlumno($idAlumno);

            $modifyQueries->buildInsertSqlIfNotExists_("alumno_comision", [
                "alumno"=>$idAlumno, 
                "comision"=>$curso->comision, 
                "estado"=>($modifyQueries->getDetailAction("alumno", $idAlumno) == "insert") ? "Ingresante" : "Incorporado", 
                "observaciones" => "Importado desde lista de alumnos"]);

            if(isset($alumno->anio_inscripcion) 
                && isset($alumno->anio_ingreso) 
                && ($alumno->anio_ingreso < $alumno->anio_inscripcion)){
                    echo " - Alumno tiene anio_ingreso menor a anio_inscripcion<br>";
            }


            $modifyQueries->process();
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            continue;
        }
        
        echo "<br><br>";
    }
    echo "</pre>";
}