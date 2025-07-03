<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\Comision_;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

add_action('admin_post_cac2_process', 'cac2_process_handle');

function cac2_process_handle() {
    $comision_id = wp_initialize_handle("fines-plugin-cac2", "cac2_process", "comision_id");

    $db = DbMy::getInstance();

    $dataProvider = $db->CreateDataProvider();
    
    /** @var Comision_ */ $comision = $dataProvider->fetchEntityByParams("\Fines2\Comision_", ["id" => $comision_id]);
    if(empty($comision)) throw new Exception("No se ha encontrado la comision");
 
    $data = trim($_POST['data']);
    $alumnosData = ValueTypesUtils::excelParseIgnorePrefix($data);
    $dnisProcesados = [];

    foreach($alumnosData as $data){
        try {
            $modifyQueries = DbMy::getInstance()->CreateModifyQueries();
            $cuilDni = Persona_::cuilDni($data["dni_cuil"]);
            if(empty($cuilDni["dni"])) 
                continue;

            if(in_array($cuilDni["dni"], $dnisProcesados)){
                continue;
            }
            $dnisProcesados[] = $cuilDni["dni"];

            $data["numero_documento"] = $cuilDni["dni"];
            $data["cuil"] = $cuilDni["cuil"];

            /** @var Persona_ */ $persona = Persona_::createByUnique("Fines2\Persona_", $data);
            if ($persona->_status === 0){
                if(!Persona_::nombreParecido($persona->toArray(), $data))
                    continue;
                $modifyQueries->buildUpdateSql($persona);
            }
            else if ($persona->_status < 0)
                $modifyQueries->buildInsertSql($persona);

            /** @var Alumno_ */ $alumno = Alumno_::createByUnique("Fines2\Alumno_", ["persona"=>$persona->id]);
            $alumno->set("plan", $comision->planificacion_->plan);
            $modifyQueries->buildPersistSqlByStatus($alumno);

            /** @var AlumnoComision_ */ $alumnoComision = AlumnoComision_::createByUnique("Fines2\AlumnoComision_", ["alumno" => $alumno->id, "comision" => $comision->id]);

            if ($alumnoComision->_status < 0){
                $alumnoComision->set("estado", ($modifyQueries->getDetailAction("alumno", $alumno->id) == "insert") ? "Ingresante" : "Incorporado");
                $alumnoComision->set("observaciones", "Importado desde lista de alumnos");
                $modifyQueries->buildInsertSql($alumnoComision);
            }
                
            if(isset($alumno->anio_inscripcion) 
                && isset($alumno->anio_ingreso) 
                && ($alumno->anio_ingreso < $alumno->anio_inscripcion)){
            }

            $modifyQueries->process();
            wp_redirect_handle("fines-plugin-cac2", "comision_id", $comision->id, "Registro realizado");  
        } catch (Exception $e) {
            wp_redirect_handle("fines-plugin-cac2", "comision_id", $comision->id, $e->getMessage());  
        }
    }

}