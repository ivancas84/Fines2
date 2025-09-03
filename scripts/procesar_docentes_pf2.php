<?php

use Fines2\ComisionDAO;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;

require_once '../db-config.php';

// Check if the file exists
if (!file_exists(DOCENTES_PATH)) {
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


/** @var Db */ $db = \App\Context::getFinesDb();
/** @var DataProvider */ $dataProvider = $db->CreateDataProvider();
$pfidComisiones = $dataProvider->fetchAllColumnByParams("comision", "pfid", ["calendario" => CALENDARIO_ID_ACTUAL]);

for($i = 0; $i < count($data); $i++) {
    $persona = $data[$i];
    echo "Procesando persona " . $i;
    if(empty($persona["numero_documento"])){
        echo "-- Persona vacía";
        continue;
    }
    echo "<pre>";
    print_r($persona);
    echo "</pre>";

    // Convertir caracteres a utf8mb3
    foreach ($persona as $key => $value) {
        if (is_string($value)) {
            $persona[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
        }
    }

    $persona = $dataProvider->fetchEntityByUnique("persona", $persona);

    
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

