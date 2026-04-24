<?php

/**
 * Verifica la existencia del alumno en programafines, 
 * si existe lo mueve de comisión, 
 * si no existe lo agrega en la comisión
 */
session_start();

use App\Context;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Persona_;
use ProgramaFines\DataAccess\AlumnoNoExisteException;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganize\Sql\Db;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';


try {
    /** @var Db */ $db = Context::getFinesDb();

    /** @var string */ $pf_session = $_SESSION["PHPSESS"];

    /** @var AlumnoComision_ */ $alumno_comision = $db->createEntityById("alumno_comision", $_POST["alumno_comision_id"]);

    /** @var array */ $data = [
        "mi_periodo" => PF_PERIODO,
        "subcategory"=> $alumno_comision->comision_->pfid,
        "apellido" => $alumno_comision->alumno_->persona_->getApellidos(),
        "nombre" => $alumno_comision->alumno_->persona_->getNombres(),
        "cuil1" => $alumno_comision->alumno_->persona_->cuil1,
        "dni_cargar" => $alumno_comision->alumno_->persona_->numero_documento,
        "cuil2" => $alumno_comision->alumno_->persona_->cuil2,
        "nacionalidad" => "Argentina",
        "sexo" => str_contains(strtolower($alumno_comision->alumno_->persona_->genero), 'a') ? 1 : 2,
    ];

    $fecha = $alumno_comision->alumno_->persona_->fecha_nacimiento;
    if(!empty($fecha)){
        $data['dia_nac'] = $fecha->format('d');
        $data['mes_nac'] = $fecha->format('n');
        $data['ano_nac'] = $fecha->format('Y');
    }

    print_r($data);

    $pfdao = new PfDAO($pf_session);
    try {
        $pfdao->openFormModificarAlumno($data["dni_cargar"]);
        echo "<strong>El alumno existe en programafines se agregará a la comisión pero no se cambiarán los datos en programafines</strong>";
        echo "<p>Si desea modificar los datos acceda a EDITAR ALUMNO (icono de lapiz)</p>";
        
        $pfdao->sendDataCambiarComisionAlumno(["dni_cargar" => $data["dni_cargar"], "comision_destion" => $data["subcategory"]]);
    } catch (AlumnoNoExisteException $ex){
        echo "<h1>El alumno no existe, se agregará a programafines y a la comisión</h1>";
        $form = $pfdao->openFormAgregarAlumno();
        $pfdao->sendDataForm1AgregarAlumno($data);
        echo $pfdao->sendDataForm2AgregarAlumno([]);
    }
} catch (Exception $ex){
    echo $ex->getMessage();
}