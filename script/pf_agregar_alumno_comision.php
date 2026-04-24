<?php

/**
 * Obtiene los datos de un alumno existente de programafines, 
 * lo busca en la base de datos de planfines2.com.ar
 * si existe modifica los datos nulos de la base local
 * si no existe lo agrega en la base local
 */
session_start();

use App\Context;
use Fines2\Model\Alumno_;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;
use Fines2\Model\Persona_;
use ProgramaFines\DataAccess\AlumnoNoExisteException;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

try {

    /** @var string */ $comision_id = $_POST["comision_id"];
    /** @var string */ $numero_documento = $_POST["numero_documento"];
    /** @var string */ $pf_session = $_SESSION["PHPSESS"];

    /** @var Db */ $db = Context::getFinesDb();

    /** @var PfDAO */ $pfdao = new PfDAO($pf_session);
    
    /** @var array */ $data = $pfdao->openFormModificarAlumno($numero_documento);
    
    /** @var Comision_ */ $comision = $db->createEntityById("comision",$comision_id);

    /** @var Persona_ */ $persona = $db->createEntityByUnique("persona",["numero_documento"=> $numero_documento]);

    /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();

    if($persona->_status > -1){ //persona existe
        $persona->ssetIfNullFromPf($data);

        if($persona->_status == 0){ //persona existe
            $resultado_persona = "La persona ya existe. No será modificada.";
        } else {
            $resultado_persona = "La persona ya existe. Se modificaron sus datos.";
            $persona->resetAndCheck();
            $persona->update($modifyQueries);
        }
    } else {
        $resultado_persona = "La persona no existe. Se creará";
        $persona->ssetNotNullFromPF($data);
        $persona->resetAndCheck();
        $persona->insert($modifyQueries);
    }

    /** @var Alumno_ */ $alumno = $db->createEntityByUnique("alumno",["persona" => $persona->get("id")]);
    $alumno->resetAndCheck();
    $alumno->set("plan", $comision->planificacion_->plan);
    $alumno->persistByStatus($modifyQueries);

    /** @var AlumnoComision_ */ $alumno_comision = $db->createEntityByUnique("alumno_comision", ["comision"=>$comision_id, "alumno"=>$alumno->id]);
    if($alumno_comision->_status > -1){ //persona existe
        $resultado_comision = "El alumno ya existe en la comisión.";
    } else {
        $alumno_comision->resetAndCheck();
        $alumno_comision->insert($modifyQueries);
        $resultado_comision = "El alumno se agregará en la comisión " . $comision->getLabel();
    }

    $modifyQueries->process();
    ValueTypesUtils::redirect($resultado_persona . " - " . $resultado_comision);

} catch (Exception $ex){
    ValueTypesUtils::redirect($ex->getMessage());
}