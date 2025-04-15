<?php

require_once 'class/ProgramaFines.php';
require_once 'class/PdoFines.php';

require_once 'includes/db_config.php';

$calendario_id = "202502110007";

try {

    echo "Crear clases de acceso a datos...<br>";
    $pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
    $programaFines = new ProgramaFines(PF_USER, PF_PASSWORD);
    echo "Clases creadas correctamente.<br><br>";

    echo "Obtener pfids de db...<br>";
    $pfids = $pdoFines->pfidsComisionesByCalendario($calendario_id);
    echo "Se obtuvieron " . count($pfids) . "pfids <br><br>";

    foreach($pfids as $pfid) {
        echo "Obteniendo lista de alumnos pf de la comisión {$pfid}...<br>";
        $alumnosPf = $programaFines->infoListaAlumnos($pfid);
        echo "Se obtuvieron " . count($alumnosPf) . " registros.<br><br>";

        echo "Obteniendo lista de alumnos db de la comisión {$pfid}...<br>";
        $alumnosDb = $pdoFines->alumnosByComisionPfidAndCalendario($pfid, $calendario_id);
        echo "Se obtuvieron " . count($alumnosDb) . " registros.<br><br>";

        foreach($alumnosPf as $alumnoPf){
            $encontrado = false;

            foreach($alumnosDb as $alumnoDb){
                if($alumnoPf["dni"] == $alumnoDb["numero_documento"]){
                    echo "Alumno existe en la comisión de DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";
                    $encontrado = true;
                    break;
                } 
            }

            if(!$encontrado) {
                echo "Alumno no existe en la comisión de DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";
                
                $alumno = $pdoFines->alumnoByNumeroDocumento($alumnoPf["dni"]);

                if($alumno){
                    echo ">> Alumno existe en DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";

                    $comisiones = $pdoFines->comisionesByAlumno($alumno->id);

                    foreach($comisiones as $comision){
                        echo ">>>> Comisión DB: {$comision->sede_nombre} - {$comision->pfid} - {$comision->periodo} - {$comision->tramo} - {$comision->estado}<br>";

                    }
                } else {
                    echo ">> Alumno no existe en DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";

                    $persona = $pdoFines->personaByNumeroDocumento($alumnoPf["dni"]);

                    if($alumno){
                        echo ">>>> Persona existe en DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";
                    } else {
                        echo ">>>> Persona no existe en DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";
                    }
                }


            }            
        }

        echo "<br>";

        foreach($alumnosDb as $alumnoDb){
            $encontrado = false;

            foreach($alumnosPf as $alumnoPf){
                if($alumnoPf["dni"] == $alumnoDb["numero_documento"]){
                    echo "Alumno existe en la comisión de PF: {$alumnoDb["numero_documento"]} - {$alumnoDb["nombres"]} {$alumnoDb["apellidos"]}<br>";
                    $encontrado = true;
                    break;
                } 
            }

            if(!$encontrado) {
                echo "Alumno no existe en la comisión de PF: {$alumnoDb["numero_documento"]} - {$alumnoDb["nombres"]} {$alumnoDb["apellidos"]}<br>";
            }
        }
        echo "<br><br>";


    }

    
    // Cerrar la conexión
    echo "Cerrando la conexión...<br\>";
    $programaFines->dispose($client);
    echo "Conexión cerrada.<br\>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br\>";
    
    // Asegurarse de cerrar la conexión en caso de error
    if (isset($client)) {
        ProgramaFines::Dispose($client);
    }
}
?>