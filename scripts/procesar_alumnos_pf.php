<?php

require_once 'class/ProgramaFines.php';

require_once 'includes/db_config.php';

$pfid = $_GET["comision_id"];

try {
    // Iniciar sesión
    echo "Iniciando sesión en ProgramaFines...<br>";
    $client = ProgramaFines::PF_Login(PF_USER, PF_PASSWORD);
    echo "Sesión iniciada correctamente.<br>";
    
    // Ejemplo 1: Obtener lista de alumnos de una comisión
    echo "Obteniendo lista de alumnos de la comisión {$comisionId}...\n";
    ProgramaFines::PF_InfoListaAlumnos($client, $comisionId);
    //echo "Se obtuvieron " . (count($listaAlumnos) - 1) . " registros.\n\n";

  
    // Cerrar la conexión
    echo "Cerrando la conexión...\n";
    ProgramaFines::Dispose($client);
    echo "Conexión cerrada.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Asegurarse de cerrar la conexión en caso de error
    if (isset($client)) {
        ProgramaFines::Dispose($client);
    }
}
?>