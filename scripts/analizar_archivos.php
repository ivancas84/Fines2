<?php
echo "<pre>";

require_once 'db_config.php';

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function analizarDirectorio($ruta) {
    global $pdo;
    
    $partes = explode(DIRECTORY_SEPARATOR, rtrim($ruta, DIRECTORY_SEPARATOR));
    if (count($partes) < 1) return;

    $ultimoDirectorio = trim($partes[count($partes) - 1]);
    preg_match('/^(\d{7,8}|\d{10})/', $ultimoDirectorio, $coincidencias);
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
    $stmt = $pdo->prepare("SELECT id FROM persona WHERE numero_documento = ?");
    $stmt->execute([$numero]);
    $persona = $stmt->fetch();

    if (!$persona) {
        $stmt = $pdo->prepare("INSERT INTO persona (id, nombres, numero_documento) VALUES (UUID(), ?, ?)");
		$stmt->execute([$ultimoDirectorio, $numero]);

		// Recuperar ID manualmente (porque UUID no es compatible con lastInsertId)
		$stmt = $pdo->prepare("SELECT id FROM persona WHERE numero_documento = ?");
		$stmt->execute([$numero]);
		$persona = $stmt->fetch();

		if (!$persona) {
			die("⛔ Error: No se pudo obtener el ID de la persona.");
		}

		$personaId = $persona['id'];

		echo "🆕 Persona agregada: $numero - $ultimoDirectorio ( $personaId ) \n";
    } else {
        $personaId = $persona['id'];
        echo "✅ Persona encontrada: $numero - $ultimoDirectorio ( $personaId ) \n";
    }

    // Verificar si el alumno existe
    $stmt = $pdo->prepare("SELECT id, anio_ingreso FROM alumno WHERE persona = ?");
    $stmt->execute([$personaId]);
    $alumno = $stmt->fetch();

    if (!$alumno) {
        $stmt = $pdo->prepare("INSERT INTO alumno (id, persona) VALUES (UUID(), ?)");
        $stmt->execute([$personaId]);

		// Recuperar ID manualmente (porque UUID no es compatible con lastInsertId)
		$stmt = $pdo->prepare("SELECT id FROM alumno WHERE persona = ?");
		$stmt->execute([$personaId]);
		$alumno = $stmt->fetch();

        echo "🆕 Alumno agregado para $numero ( " .  $alumno["id"] . " ) \n";
        $anioIngresoExistente = null; // No existe aún, no hay valor previo
    } else {
        $anioIngresoExistente = $alumno['anio_ingreso']; // Guardar el valor existente
        echo "✅ Alumno encontrado para $numero ( " .  $alumno["id"] . " ) \n";
    }

    // Determinar el valor de anio_ingreso
    if (stripos($ultimoDirectorio, 'primero') !== false) {
        echo "📄 Año ingreso definido: primero \n";
        $anioIngreso = 1;
    } elseif (stripos($ultimoDirectorio, 'segundo') !== false) {
        echo "📄 Año ingreso definido: segundo \n";
        $anioIngreso = 2;
    } elseif (stripos($ultimoDirectorio, 'tercero') !== false) {
        echo "📄 Año ingreso definido: tercero \n";
        $anioIngreso = 3;
    } else {
        echo "📄 Año ingreso no definido, manteniendo valor existente: $anioIngresoExistente \n";
        $anioIngreso = $anioIngresoExistente; // Mantiene el valor en la DB si ya existía
    }

// Determinar el valor de previas_completas
    $previasCompletas = (stripos($ultimoDirectorio, 'deudores') !== false) ? 0 : 1;
	echo "📄 Previas completas: " . ($previasCompletas ? "Sí" : "No (debe ir a deudores)") . "\n";

    // Actualizar datos del alumno con documentos encontrados
    if ($alumno || isset($personaId)) {
        $stmt = $pdo->prepare("UPDATE alumno SET 
            tiene_dni = ?, 
            tiene_partida = ?, 
            tiene_certificado = ?, 
            tiene_constancia = ?,
            anio_ingreso = ?,
            previas_completas = ?
            WHERE persona = ?");
        
        // Actualizar los documentos, considerando "ana" como sinónimo de "cert"
        $stmt->execute([
            isset($archivosFiltrados['dni']) ? 1 : 0,
            isset($archivosFiltrados['part']) ? 1 : 0,
            isset($archivosFiltrados['cert']) || isset($archivosFiltrados['ana']) ? 1 : 0,
            isset($archivosFiltrados['cons']) ? 1 : 0,
            $anioIngreso, // Mantiene el valor si no se pudo determinar uno nuevo
            $previasCompletas,
			$personaId
        ]);
        echo "📄 Documentos actualizados para $numero: " . implode(", ", array_keys($archivosFiltrados)) . "\n";
		echo "<br>";
    }
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


?>
