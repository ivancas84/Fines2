<?php

require_once 'class/ProgramaFines.php';

require_once 'includes/db_config.php';


$calendario = $_GET["calendario_id"];

$pdoFines = new PdoFines($db_host, $db_name, $db_user, $db_pass);

try {

    echo "Iniciando sesión en ProgramaFines...<br\>";
    $client = ProgramaFines::PF_Login(PF_USER, PF_PASSWORD);
    echo "Sesión iniciada correctamente.<br\><br\>";
    
    $pfids = $pdoFines->PfidsComisionesByCalendario($calendario);

    echo "Obteniendo lista de alumnos pf de la comisión {$pfid}...<br\>";
    $alumnos = ProgramaFines::PF_InfoListaAlumnos($client, $pfid);
    echo "Se obtuvieron " . (count($listaAlumnos) - 1) . " registros.<br\><br\>";

    echo "Obteniendo lista de alumnos db de la comisión {$pfid}...<br\>";
    $alumnos = ProgramaFines::PF_InfoListaAlumnos($client, $pfid);
    echo "Se obtuvieron " . (count($listaAlumnos) - 1) . " registros.<br\><br\>";

    
    // Cerrar la conexión
    echo "Cerrando la conexión...<br\>";
    ProgramaFines::Dispose($client);
    echo "Conexión cerrada.<br\>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br\>";
    
    // Asegurarse de cerrar la conexión en caso de error
    if (isset($client)) {
        ProgramaFines::Dispose($client);
    }
}
?>