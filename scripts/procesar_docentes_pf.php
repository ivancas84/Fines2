<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once 'includes/db_config.php';
require_once 'includes/queries_fines.php';



$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);


// Path to your JSON file
$jsonFile = $rutaBase . '/data.json';

// Check if the file exists
if (!file_exists($jsonFile)) {
    die("Error: JSON file not found.");
}

// Read the file contents
$dataJson = file_get_contents($jsonFile);



// Decode JSON
$dataJson = trim($dataJson); // Remove whitespace
$data = json_decode($dataJson, true);

if ($data === null) {
    echo "<h3>JSON Decode Error:</h3>" . json_last_error_msg();
    die();
} 


$id_calendario = $_GET["calendario"];

if(empty($id_calendario)) {
    echo "Calendario no definido, no se procesaran los cargos";
} else {
    $pfidComisiones = pdoFines_pfidComisionesById($pdo, $id_calendario);
}

foreach ($data as $persona) {
    echo "******************************<br>";
    print_r($persona);
    // Check if the person exists
    $stmt = $pdo->prepare("SELECT * FROM persona WHERE numero_documento = ?");
    $stmt->execute([$persona['numero_documento']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array
    $date = new DateTime();
    $date->setDate($persona['anio_nacimiento'], $persona['mes_nacimiento'], $persona['dia_nacimiento']);
    $fecha_nacimiento = $date->format('Y-m-d'); // Converts to 'YYYY-MM-DD' format
    $email_abc = !empty(trim($persona["email_abc"])) ? trim($persona["email_abc"]) : null;

    if(is_null($email_abc)) echo "Es null email abc<br>";
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

        if ($update) {
            echo "Persona actualizada.". "<br/>";
        } else {
            echo "Persona no actualizada (mismos valores)<br/>";
        }
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

        if ($insert) {
            echo "Se ha insertado la persona<br/>";
        }
    }




    if(empty($pfidComisiones)) continue;

    foreach($persona["cargos"]  as $cargo){
        if(in_array($cargo["comision"], $pfidComisiones)){
            echo "***** PROCESAR CARGO " . $cargo["comision"] . " " . $cargo["codigo"] . "*****<br>";
            $id_curso = pdoFines_idCurso__By_ComisionPfid_AsignaturaCodigo_calendario($pdo, $cargo["comision"], $cargo["codigo"], $id_calendario);

            if(!$id_curso){
                echo "No existe curso<br>";
                die();
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
        
                if ($insert) {
                    echo "Se ha insertado la toma<br/>";
                }  else {
                    $errorInfo = $stmt->errorInfo(); // Get last error
                    echo "Error: No se pudo insertar la toma.<br/>";
                    echo "SQLSTATE: " . $errorInfo[0] . "<br/>";
                    echo "Código de error: " . $errorInfo[1] . "<br/>";
                    echo "Mensaje: " . $errorInfo[2] . "<br/>";
                }      
            }
            else if($toma["docente"] != $id_persona) echo "Existe una toma con un docente diferente. No se ejecutará ningun acción<br>";
            else echo "Existe una toma con un el mismo docente. No se ejecutará ningun acción<br>";
        }
    }
    
    // Insert or update cargos (delete old and insert new)
    //$pdo->prepare("DELETE FROM cargos WHERE numero_documento = ?")->execute([$persona['numero_documento']]);

    /*
    foreach ($persona['cargos'] as $cargo) {
        $sql = "INSERT INTO cargos (numero_documento, comision, codigo) VALUES (?, ?, ?)";
        $pdo->prepare($sql)->execute([$persona['numero_documento'], $cargo['comision'], $cargo['codigo']]);
    }*/
}


?>

