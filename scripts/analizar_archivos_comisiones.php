<?php
echo "<pre>";

echo "Analizando archivos de comisiones...<br>";
require_once 'includes/db_config.php';

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function analizarDirectorio($ruta) {
    global $pdo;
    
    $partes = explode(DIRECTORY_SEPARATOR, rtrim($ruta, DIRECTORY_SEPARATOR));
    if (count($partes) < 1) return;

    $idComisionActual = $_GET['comision_id'] ?? null;

    $ultimoDirectorio = trim($partes[count($partes) - 1]);
    preg_match('/\d{7,10}/', $ultimoDirectorio, $coincidencias);
    $numero = $coincidencias[1] ?? null;
    if (!$numero) return;

    $archivosFiltrados = [];
    if (is_dir($ruta)) {
        foreach (scandir($ruta) as $archivo) {
            if (!in_array($archivo, ['.', '..'])) {
                foreach (["dni", "part", "cert", "cons", "ana"] as $clave) {
                    if (stripos($archivo, $clave) !== false) {
                        $archivosFiltrados[$clave] = true;
                    }
                }
            }
        }
    }
    

    // Verificar si la persona existe
    $stmt = $pdo->prepare("SELECT * FROM persona WHERE numero_documento = ?");
    $stmt->execute([$numero]);
    $persona = $stmt->fetch();


    if (!$persona) {
		echo "🆕 Persona inexistente: $numero - $ultimoDirectorio \n";
        return;
    }
    $personaId = $persona['id'];
    echo "✅ Persona encontrada: $numero - $ultimoDirectorio ( $personaId ) \n";

    // Verificar si el alumno existe
    $stmt = $pdo->prepare("SELECT * FROM alumno WHERE persona = ?");
    $stmt->execute([$personaId]);
    $alumno = $stmt->fetch();

    if (!$alumno) {
        echo "🆕 Alumno inexistente para $numero \n";
        return;
    }
     
    $alumnoId = $alumno['id'];
    echo "✅ Alumno encontrado para $numero ( " .  $alumno["id"] . " ) \n";
    
    procesar_comisiones($pdo, $alumno["id"], $idComisionActual);

    echo "<br><br><br>";


    
}

function recorrerEstructura($rutaBase) {
    foreach (scandir($rutaBase) as $elemento) {
        if ($elemento === '.' || $elemento === '..') continue;
        $rutaCompleta = $rutaBase . DIRECTORY_SEPARATOR . $elemento;
        if (is_dir($rutaCompleta)) {
            analizarDirectorio($rutaCompleta);
            recorrerEstructura($rutaCompleta);
        }
    }
}

recorrerEstructura($rutaBase);


function procesar_comisiones($pdo, $alumnoId, $idComisionActual) {
    // Fetch and display data from alumno_comision
    try {
        $stmt = $pdo->query("
        SELECT comision.id, comision.pfid, alumno_comision.estado, sede.nombre,
        CONCAT (calendario.anio, '/', calendario.semestre) AS periodo,
        CONCAT (planificacion.anio, '-', planificacion.semestre) AS tramo
        FROM alumno_comision 
        INNER JOIN comision ON alumno_comision.comision = comision.id
        INNER JOIN sede ON comision.sede = sede.id
        INNER JOIN calendario ON comision.calendario = calendario.id
        INNER JOIN planificacion ON comision.planificacion = planificacion.id
        INNER JOIN plan ON planificacion.plan = plan.id
        WHERE alumno = '$alumnoId'
        ORDER BY calendario.anio DESC, calendario.semestre DESC"
        );
    $alumnoComisionData = $stmt->fetchAll();

    if ($alumnoComisionData) {
        echo "📋 Comisiones del alumno:<br/>";
        foreach ($alumnoComisionData as $row) {
            echo $row['nombre'] . " " . ($row['pfid'] ?? 'N/A') . " Tramo: " . $row["tramo"] . " Estado: " . $row['estado'] . " Período: " . $row['periodo'];
            if($idComisionActual && strtolower($row["id"]) == strtolower($idComisionActual)){
                echo " <strong>COMISION ACTUAL!</strong>.";
            }
            echo "<br/>";
        }
    } else {
        echo "⚠️ No se encuentra registrado en ninguna comision.\n";
    }
    } catch (PDOException $e) {
        echo "Error al procesar comisiones: " . $e->getMessage();
    }
}