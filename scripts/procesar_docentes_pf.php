<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'includes/queries_fines.php';



$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
$pdo->exec("SET NAMES 'utf8mb3'");


// Path to your JSON file
$jsonFile = $rutaBase . '/data2.json';

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


$id_calendario = "202502110007";

if(empty($id_calendario)) {
    echo "Calendario no definido, no se procesaran los cargos";
} else {
    $pfidComisiones = pdoFines_pfidComisionesById($pdo, $id_calendario);
}

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
    $stmt = $pdo->prepare("SELECT * FROM persona WHERE numero_documento = ?");
    $stmt->execute([$persona['numero_documento']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array
    $date = new DateTime();
    $date->setDate($persona['anio_nacimiento'], $persona['mes_nacimiento'], $persona['dia_nacimiento']);
    $fecha_nacimiento = $date->format('Y-m-d'); // Converts to 'YYYY-MM-DD' format
    $email_abc = !empty(trim($persona["email_abc"])) ? trim($persona["email_abc"]) : null;

    if ($result) {

        $id_persona = $result["id"];
        // Update existing person
        $sql = "UPDATE persona 
                SET nombres = ?, apellidos = ?, descripcion_domicilio = ?, 
                    dia_nacimiento = ?, mes_nacimiento = ?, anio_nacimiento = ?, 
                    fecha_nacimiento = ?,
                    telefono = ?, email_abc = ?
                WHERE numero_documento = ?";
        $update = $pdo->prepare($sql)->execute([
            $persona['nombres'], $persona['apellidos'], $persona['descripcion_domicilio'],
            $persona['dia_nacimiento'], $persona['mes_nacimiento'], $persona['anio_nacimiento'],
            $fecha_nacimiento,
            $persona['telefono'], $email_abc, $persona['numero_documento']
        ]);

        echo $update ? "Persona actualizada.<br/>" : "Persona no actualizada (mismos valores)<br/>";

    } else {
        $id_persona = uniqid();
        // Insert new person
        $sql = "INSERT INTO persona (id, numero_documento, nombres, apellidos, descripcion_domicilio, 
                                      dia_nacimiento, mes_nacimiento, anio_nacimiento, fecha_nacimiento, telefono, email_abc) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert = $pdo->prepare($sql)->execute([
            $id_persona,
            $persona['numero_documento'], $persona['nombres'], $persona['apellidos'],
            $persona['descripcion_domicilio'], $persona['dia_nacimiento'], $persona['mes_nacimiento'], 
            $persona['anio_nacimiento'], $fecha_nacimiento, $persona['telefono'], $email_abc
        ]);

        echo $insert ? "Se ha insertado la persona<br/>" : "Error al insertar la persona.<br/>";

    }

    if(empty($pfidComisiones)) continue;

    foreach($persona["cargos"]  as $cargo){
        if(in_array($cargo["comision"], $pfidComisiones)){
            echo "***** PROCESAR CARGO " . $cargo["comision"] . " " . $cargo["codigo"] . "*****<br>";
            $id_curso = pdoFines_idCurso__By_ComisionPfid_AsignaturaCodigo_calendario($pdo, $cargo["comision"], $cargo["codigo"], $id_calendario);

            if(!$id_curso){
                echo "No existe curso<br>";
                continue;
            }

            $toma = pdoFines_tomaActiva__By_comisionPfid_asignaturaCodigo_calendario($pdo, $cargo["comision"], $cargo["codigo"], $id_calendario);
            
            if(!$toma) {
                echo "No existe toma de posesion en el cargo<br>";
                $id_toma = uniqid();
                // Insert new person
                $sql = "INSERT INTO toma (id, estado, tipo_movimiento, estado_contralor, 
                                              curso, docente) 
                        VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);

                $insert = $stmt->execute([
                    $id_toma,
                    'Pendiente', 'AI', 'Pasar',
                    $id_curso, $id_persona
                ]);
        
                echo $insert ?  "Se ha insertado la toma<br/>" : "Error al insertar la toma: " . $stmt->errorInfo();
            }

            else echo ($toma["docente"] != $id_persona) ? 
                "Existe una toma con un docente diferente. No se ejecutará ningun acción<br>"
                : "Existe una toma con un el mismo docente. No se ejecutará ningun acción<br>";
        }
    }
    
}


?>

