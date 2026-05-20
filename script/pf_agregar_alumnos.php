<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;
use ProgramaFines\DataAccess\AlumnoNoExisteException;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganiza\Sql\DataProvider;
use SqlOrganiza\Sql\Db;

session_start();

/**
 * Migración / Sincronización de alumnos a programafines.ar
 * Muestra progreso en tiempo real
 */

// ==================== CONFIGURACIÓN ANTI-BUFFERING ====================
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
ini_set('implicit_flush', true);
ob_implicit_flush(true);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('X-Accel-Buffering: no'); // Importante para nginx

set_time_limit(0);           // Sin límite de tiempo
ignore_user_abort(true);     // Seguir aunque el usuario cierre la pestaña

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Sincronizando Alumnos - Progreso en Tiempo Real</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #1a73e8; }
        .log { margin: 10px 0; padding: 10px; border-left: 4px solid #1a73e8; background: #f8f9fa; }
        .success { border-color: #34a853; }
        .error { border-color: #ea4335; }
        pre { background: #f1f3f4; padding: 10px; overflow: auto; max-height: 300px; }
    </style>
</head>
<body>
<h1>🚀 Iniciando sincronización de alumnos...</h1>
";

// ==================== LÓGICA PRINCIPAL ====================

$pf_session = $_SESSION["PHPSESS"] ?? null;

if (empty($pf_session)) {
    die("<p style='color:red'>❌ La sesión está vacía</p></body></html>");
}

/** @var Db */ $db = \App\Context::getFinesDb();
/** @var string */ $comision_id = $_POST["comision_id"] ?? null;

if (empty($comision_id)) {
    die("<p style='color:red'>❌ Falta el parámetro comision_id</p></body></html>");
}

/** @var Comision_ */
$comision = $db->createEntityById("comision", $comision_id);

/** @var AlumnoComision_[] */
$alumnos_comision = AlumnoComisionDAO::alumnosComision($comision_id);

if (empty($alumnos_comision)) {
    die("<p>No hay alumnos en esta comisión.</p></body></html>");
}

$pf = new PfDAO($pf_session);

// Obtener alumnos ya existentes en Programafines
$alumnos_pf = $pf->getListaAlumnos($comision->pfid, PF_PERIODO);

$docs_pf = [];
foreach ($alumnos_pf as $alumno) {
    if (!empty($alumno["numero_documento"])) {
        $docs_pf[] = trim((string)$alumno["numero_documento"]);
    }
}
$docs_pf = array_unique($docs_pf);

$total = count($alumnos_comision);
echo "<p><strong>Total de alumnos a procesar: {$total}</strong></p><hr>";

// ==================== PROCESAMIENTO CON PROGRESO EN TIEMPO REAL ====================
foreach ($alumnos_comision as $i => $ac) {
    $persona = $ac->alumno_->persona_;
    $dni = $persona->numero_documento;
    $nombre = $persona->getLabel();

    $progress = $i + 1;
    echo "<div class='log'>
            <strong>[{$progress}/{$total}] Procesando:</strong> {$nombre} (DNI: {$dni})<br>";

    try {
        $data = $ac->toArrayPF(); // Ajusta según tu modelo

        if (in_array($dni, $docs_pf)) {
            // Ya existe en Programafines → Modificar
            echo "<span class='success'>✓ El alumno ya existe. Se actualizarán sus datos.</span><br>";

            $old_data = $pf->openFormModificarAlumno($dni);
            echo "<p>Datos actuales:</p><pre>" . htmlspecialchars(print_r($old_data, true)) . "</pre>";

            echo "<p>Datos nuevos que se enviarán:</p><pre>" . htmlspecialchars(print_r($data, true)) . "</pre>";

            $pf->sendDataFormModificarAlumno($data);

        } else {
            // No existe → Intentar agregar
            echo "<span class='success'>➕ El alumno no existe. Se agregará.</span><br>";

            try {
                // Primero intentamos como si existiera (por si está en otra comisión)
                $old_data = $pf->openFormModificarAlumno($dni);
                echo "<p>Existe pero en otra comisión → Modificando y cambiando comisión...</p>";

                $pf->sendDataFormModificarAlumno($data);
                $pf->transferirAlumnoAComision($data["dni_cargar"], $data["subcategory"]);

            } catch (AlumnoNoExisteException $ex) {
                // Realmente no existe → Crear nuevo
                echo "<p>Alumno completamente nuevo → Insertando...</p>";
                echo "<p>Datos a insertar:</p><pre>" . htmlspecialchars(print_r($data, true)) . "</pre>";

                $pf->openFormAgregarAlumno();
                $pf->sendDataForm1AgregarAlumno($data);
                $pf->sendDataForm2AgregarAlumno([]);
            }
        }

        echo "<span class='success'>✅ Procesado correctamente</span>";

    } catch (Exception $ex) {
        echo "<span class='error'>❌ Error: " . htmlspecialchars($ex->getMessage()) . "</span>";
    }

    echo "</div>";
    flush();           // ← Muy importante: envía la salida inmediatamente
    ob_flush();
}

// ==================== FINAL ====================
echo "<hr><h2>🎉 Proceso finalizado</h2>";
echo "<p><strong>Se procesaron {$total} alumnos.</strong></p>";
echo "</body></html>";

flush();