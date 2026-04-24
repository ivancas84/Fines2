<?php

//Eliminar todas las calificaciones del curso
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

    /** @var Persona_ */ $persona = $db->createEntityById("persona", $_REQUEST["persona_id"]);

    $data = $persona->toArrayPF();

    $pfdao = new PfDAO($pf_session);
    try {
        echo "<h1>Modificar alumno en programafines</h1>";
        echo "<p>ATENCIÓN: Se van a modificar los datos del alumno pero no de la comisión, si desea modificar la comisión hagalo desde la lista de alumnos";
        
        $old_data = $pfdao->openFormModificarAlumno($data["dni_cargar"]);
        echo "<h3>Datos actuales del alumno que serán modificados</h3>";
        echo "<pre>";
        print_r($old_data);
        echo "</pre>";
        echo "<h3>Datos nuevos del alumno que modificarán los anteriores</h3>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $html = $data = $pfdao->sendDataFormModificarAlumno($data);
        echo $html;
    } catch (AlumnoNoExisteException $ex){
        echo "<h1>Modificar alumno en programafines</h1>";
        echo "<p>El alumno no existe en programa fines, no se realizará ninguna modificación<p>";
    }
} catch (Exception $ex){
    echo $ex->getMessage();
}