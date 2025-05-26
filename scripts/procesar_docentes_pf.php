<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'includes/queries_fines.php';
require_once 'class/PdoFines.php';

$pdoFines = new PdoFines(DB_HOST_FINES, DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES);
$jsonFile = RUTA_BASE . '/data2.json';

// Check if the file exists
if (!file_exists($jsonFile)) {
    die("Error: JSON file not found.");
}

// Read the file contents
$dataJson = file_get_contents($jsonFile);
$dataJson = mb_convert_encoding($dataJson, 'UTF-8', 'auto');

// Decode JSON
$dataJson = trim($dataJson); // Remove whitespace
$data = json_decode($dataJson, true);

if ($data === null) {
    echo "<h3>JSON Decode Error:</h3>" . json_last_error_msg();
    die();
} 

$pfidComisiones = $pdoFines->pfidsComisionesByCalendario(CALENDARIO_ID);

foreach ($data as $persona) {
    echo "******************************<br>";
    if(empty($persona["numero_documento"])){
        echo "Persona vacía";
        continue;
    }
    print_r($persona);

    // Convertir caracteres a utf8mb3
    foreach ($persona as $key => $value) {
        if (is_string($value)) {
            $persona[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
        }
    }

    // Check if the person exists
    $result = $pdoFines->personaByNumeroDocumento($persona['numero_documento'], PDO::FETCH_ASSOC);
    $date = new DateTime();
    $date->setDate($persona['anio_nacimiento'], $persona['mes_nacimiento'], $persona['dia_nacimiento']);
    $persona["fecha_nacimiento"] = $date->format('Y-m-d'); // Converts to 'YYYY-MM-DD' format
    $persona["email_abc"] = !empty(trim($persona["email_abc"])) ? trim($persona["email_abc"]) : null;

    if ($result) {
        $persona["id"] = $result["id"];
        $update = $pdoFines->updatePersonaArray($persona);
        echo $update ? "Persona actualizada.<br/>" : "Persona no actualizada (mismos valores)<br/>";

    } else {
        $persona["id"] = uniqid();
        $insert = $pdoFines->insertPersonaArray($persona);
        echo $insert ? "Se ha insertado la persona<br/>" : "Error al insertar la persona.<br/>";
    }

    if(empty($pfidComisiones)) continue;

    foreach($persona["cargos"]  as $cargo){
        if(in_array($cargo["comision"], $pfidComisiones)){
            echo "***** PROCESAR CARGO " . $cargo["comision"] . " " . $cargo["codigo"] . "*****<br>";
            $id_curso = $pdoFines->idCursoByParams($cargo["comision"], $cargo["codigo"], CALENDARIO_ID);

            if(!$id_curso){
                echo "<strong>No existe curso</strong><br>";
                continue;
            }

            $toma = $pdoFines->tomaActivaByParams($cargo["comision"], $cargo["codigo"], CALENDARIO_ID);
            
            if(!$toma) {
                echo "No existe toma de posesion en el cargo<br>";

                $insert =$pdoFines->insertTomaPendienteAI($id_curso, $persona["id"]);	
        
                echo $insert ?  "<strong>Se ha insertado la toma</strong><br/>" : "<strong>Error al insertar la toma: " . $stmt->errorInfo() . "</strong><br/>";
            }

            else echo ($toma["docente"] != $persona["id"]) ? 
                "<strong>Existe una toma con un docente diferente. No se ejecutará ningun acción</strong><br>"
                : "Existe una toma con un el mismo docente. No se ejecutará ningun acción<br>";
        }
    }
    
}


?>

