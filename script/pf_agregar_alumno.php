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

    $data = $alumno_comision->toArrayPF();

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

        echo "<strong>El alumno existe en programafines se modificaran los datos y agregará a la comisión</strong>";
        echo $pfdao->sendDataFormModificarAlumno($data);
        echo "<br>";
        echo $pfdao->transferirAlumnoAComision($data["dni_cargar"], $data["subcategory"]);
    } catch (AlumnoNoExisteException $ex){
        echo "<h1>El alumno no existe, se agregará a programafines y a la comisión</h1>";
        $form = $pfdao->openFormAgregarAlumno();
        $pfdao->sendDataForm1AgregarAlumno($data);
        echo $pfdao->sendDataForm2AgregarAlumno([]);
    }
} catch (Exception $ex){
    echo $ex->getMessage();
}