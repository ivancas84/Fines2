<?php

require_once 'class/ProgramaFines.php';
require_once 'class/PdoFines.php';

require_once 'includes/db_config.php';

$calendario_id = "202502110007";

try {

    $pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
    $programaFines = new ProgramaFines(PF_USER, PF_PASSWORD);

    $pfids = $pdoFines->pfidsComisionesByCalendario($calendario_id);

    foreach($pfids as $pfid) {
        echo "<h2>Procesando comisión {$pfid}</h2>";
        $alumnosPf = $programaFines->infoListaAlumnos($pfid);
        $alumnosDb = $pdoFines->alumnosByComisionPfidAndCalendario($pfid, $calendario_id);

        echo "<strong>Análisis de DB</strong><br>";
        $cantidadAlumnosExistentes = 0;
        foreach($alumnosPf as $alumnoPf){
            $encontrado = false;

            foreach($alumnosDb as $alumnoDb){
                if($alumnoPf["dni"] == $alumnoDb["numero_documento"]){
                    $cantidadAlumnosExistentes++;
                    $encontrado = true;
                    break;
                } 
            }

            if(!$encontrado) {
                echo "Alumno existe en comisión PF / no existe en comisión DB: {$alumnoPf["dni"]} - {$alumnoPf["nombres"]} {$alumnoPf["apellidos"]}<br>";
                
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

                echo "<br><br>";
            }            
        }

        echo "Cantidad de alumnos existentes en la comisión DB que están en PF: {$cantidadAlumnosExistentes}<br>";

        echo "<strong>Análisis de PF</strong><br>";

        $cantidadAlumnosExistentes = 0;

        foreach($alumnosDb as $alumnoDb){
            $encontrado = false;
        
            foreach($alumnosPf as $alumnoPf){
                if($alumnoPf["dni"] == $alumnoDb["numero_documento"]){
                    $cantidadAlumnosExistentes++;	
                    $encontrado = true;
                    break;
                } 
            }

            if(!$encontrado) {
                echo "Alumno existe en comisión DB / no existe en comisión PF: {$alumnoDb["numero_documento"]} - {$alumnoDb["nombres"]} {$alumnoDb["apellidos"]}<br>";
            }
        }

        echo "Cantidad de alumnos existentes en la comisión PF que están en DB: {$cantidadAlumnosExistentes}<br>";


    }
    
    $programaFines->dispose($client);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br\>";
    
    // Asegurarse de cerrar la conexión en caso de error
    if (isset($client)) {
        ProgramaFines::Dispose($client);
    }
}
?>