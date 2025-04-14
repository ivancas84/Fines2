<?php

require_once 'class/ProgramaFines.php';
require_once 'class/PdoFines.php';

require_once 'includes/db_config.php';

$calendario = $_GET["calendario_id"];

try {

    echo "Crear clases de acceso a datos...<br>";
    $pdoFines = new PdoFines($db_host, $db_name, $db_user, $db_pass);
    $programaFines = new ProgramaFines(PF_USER, PF_PASSWORD);
    echo "Clases creadas correctamente.<br><br>";

    echo "Obtener pfids de db...<br>";
    $pfids = $pdoFines->pfidsComisionesByCalendario($calendario);
    echo "Se obtuvieron " . count($pfids) . "pfids <br><br>";

    foreach($pfids as $pfid) {
        echo "Obteniendo lista de alumnos pf de la comisión {$pfid}...<br>";
        $alumnosPf = $programaFines->infoListaAlumnos($pfid);
        echo "Se obtuvieron " . count($alumnosPf) . " registros.<br><br>";

        echo "Obteniendo lista de alumnos db de la comisión {$pfid}...<br>";
        $alumnosDb = $pdoFines->alumnosByComisionPfid($pfid);
        echo "Se obtuvieron " . count($alumnosDb) . " registros.<br><br>";

        foreach($alumnosPf as $alumnoPf){
            foreach($alumnosDb as $alumnoDb){
                if($alumnoPf["dni"] == $alumnoDb["numero_documento"]){
                    echo "Alumno encontrado: {$alumnoPf["dni"]} - {$alumnoPf["nombre"]} {$alumnoPf["apellido"]}<br>";
                } else {
                    echo "Alumno no encontrado: {$alumnoPf["dni"]} - {$alumnoPf["nombre"]} {$alumnoPf["apellido"]}<br>";
                }
            }
        }

        die();

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