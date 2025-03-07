<?php
echo "<pre>";

require_once 'includes/db_config.php';

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
    
    $idComisionActual = $_GET['comision_id'] ?? null;

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
    $stmt = $pdo->prepare("SELECT * FROM alumno WHERE persona = ?");
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
        $confirmadoDireccionExistente = null; // No existe aún, no hay valor previo
        $estadoInscripcionExistente = null; // No existe aún, no hay valor previo
    } else {
        $anioIngresoExistente = $alumno['anio_ingreso']; // Guardar el valor existente
        $confirmadoDireccionExistente = $alumno['confirmado_direccion']; // No existe aún, no hay valor previo
        $estadoInscripcionExistente = $alumno['estado_inscripcion']; // No existe aún, no hay valor previo
        echo "✅ Alumno encontrado para $numero ( " .  $alumno["id"] . " ) \n";
    }

    // Determinar el valor de anio_ingreso
    if (stripos($ultimoDirectorio, 'primero') !== false) {
        echo "📄 Año ingreso definido: primero \n";
        $confirmadoDireccion = 1;
        $anioIngreso = 1;
        $estadoInscripcion = "Correcto";
    } elseif (stripos($ultimoDirectorio, 'segundo') !== false) {
        echo "📄 Año ingreso definido: segundo \n";
        $confirmadoDireccion = 1;
        $anioIngreso = 2;
        $estadoInscripcion = "Correcto";
    } elseif (stripos($ultimoDirectorio, 'tercero') !== false) {
        echo "📄 Año ingreso definido: tercero \n";
        $confirmadoDireccion = 1;
        $anioIngreso = 3;
        $estadoInscripcion = "Correcto";

    } else {
        echo "📄 Año ingreso no definido, manteniendo valor existente: $anioIngresoExistente \n";
        $confirmadoDireccion = $confirmadoDireccionExistente;
        $anioIngreso = $anioIngresoExistente; // Mantiene el valor en la DB si ya existía
        $estadoInscripcion = $estadoInscripcionExistente; // Mantiene el valor en la DB si ya existía

    }

    // Determinar el valor de previas_completas
    $previasCompletas = (stripos($ultimoDirectorio, 'pendiente') !== false) ? 0 : 1;
	echo "📄 Previas completas: " . ($previasCompletas ? "Sí" : "No (debe completar materias pendiente)") . "\n";

    // Actualizar datos del alumno con documentos encontrados
    if ($alumno || isset($personaId)) {
        $stmt = $pdo->prepare("UPDATE alumno SET 
            tiene_dni = ?, 
            tiene_partida = ?, 
            tiene_certificado = ?, 
            tiene_constancia = ?,
            anio_ingreso = ?,
            previas_completas = ?,
            confirmado_direccion = ?,
            estado_inscripcion = ?
            WHERE persona = ?");
        
        // Actualizar los documentos, considerando "ana" como sinónimo de "cert"
        $stmt->execute([
            isset($archivosFiltrados['dni']) ? 1 : 0,
            isset($archivosFiltrados['part']) ? 1 : 0,
            isset($archivosFiltrados['cert']) || isset($archivosFiltrados['ana']) ? 1 : 0,
            isset($archivosFiltrados['cons']) ? 1 : 0,
            $anioIngreso, // Mantiene el valor si no se pudo determinar uno nuevo
            $previasCompletas,
            $confirmadoDireccion,
            $estadoInscripcion,
			$personaId
        ]);
        echo "📄 Documentos actualizados para $numero: " . implode(", ", array_keys($archivosFiltrados)) . "\n";
		echo "<br>";
    }

    if($alumno){
        procesar_comisiones($pdo, $alumno["id"], $idComisionActual);
    }
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

    $existeComisionActual = false;
    if ($alumnoComisionData) {
        echo "📋 Comisiones del alumno:<br/>";
        foreach ($alumnoComisionData as $row) {
            echo $row['nombre'] . " " . ($row['pfid'] ?? 'N/A') . " Tramo: " . $row["tramo"] . " Estado: " . $row['estado'] . " Período: " . $row['periodo'];
            if($idComisionActual && strtolower($row["id"]) == strtolower($idComisionActual)){
                echo " <strong>COMISION ACTUAL!</strong>.";
                $existeComisionActual = true;
            }
            echo "<br/>";
        }
    } else {
        echo "⚠️ No se encuentra registrado en ninguna comision.\n";
    }

    if (!$existeComisionActual && $idComisionActual) {
        $stmt = $pdo->prepare("SELECT pfid FROM comision WHERE id = ?");
        $stmt->execute([$idComisionActual]);
        $comision = $stmt->fetch();

        if ($comision) {
            try {
                $stmt = $pdo->prepare("INSERT INTO alumno_comision (id, alumno, comision, estado) VALUES (UUID(), ?, ?, 'Activo')");
                $stmt->execute([$alumnoId, $idComisionActual]);

                echo "✅ Nueva comisión registrada para el alumno " . $comision["pfid"] . " <br/>";
            } catch (PDOException $e) {
                echo "⛔ Error al insertar en 'alumno_comision': " . $e->getMessage();
            }
        } else {
            echo "⚠️ La comisión con ID $idComisionActual no existe en la base de datos. No se realizará la inscripción.<br/>";
        }
    }

    } catch (PDOException $e) {
        echo "⛔ Error al consultar la tabla: " . $e->getMessage();
    }
}
?>
