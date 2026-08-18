<?php

declare(strict_types=1);

/**
 * Diagnóstico temporal de rutas. Subir junto al index.php de abcconstancias.com.ar
 * y abrir: https://abcconstancias.com.ar/debug-rutas.php
 * Borrar este archivo cuando termines.
 */
header('Content-Type: text/html; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

$publicDir = __DIR__;
$appRoot = dirname($publicDir);
if (!is_dir($appRoot . DIRECTORY_SEPARATOR . 'app') && is_dir($publicDir . DIRECTORY_SEPARATOR . 'app')) {
    $appRoot = $publicDir;
}

$indexFile = $publicDir . DIRECTORY_SEPARATOR . 'index.php';
$controllerFile = $appRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'ConstanciaController.php';
$viewFile = $appRoot . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'constancias' . DIRECTORY_SEPARATOR . 'toma_posesion_form.php';
$envFile = $appRoot . DIRECTORY_SEPARATOR . '.env';

$indexContents = is_file($indexFile) ? (string) file_get_contents($indexFile) : '';
$controllerContents = is_file($controllerFile) ? (string) file_get_contents($controllerFile) : '';

$envBasePath = '';
$envDebug = '';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_starts_with($line, 'APP_BASE_PATH=')) {
            $envBasePath = trim(substr($line, strlen('APP_BASE_PATH=')), " \t\"'");
        }
        if (str_starts_with($line, 'APP_DEBUG=')) {
            $envDebug = trim(substr($line, strlen('APP_DEBUG=')), " \t\"'");
        }
    }
}

$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');
$uriPath = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$scriptName = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
$detectedBase = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$effectiveBase = $envBasePath !== '' ? rtrim($envBasePath, '/') : $detectedBase;
$path = $uriPath;
if ($effectiveBase !== '' && str_starts_with($path, $effectiveBase)) {
    $path = substr($path, strlen($effectiveBase)) ?: '/';
}
$path = '/' . trim($path, '/');

$indexHasGetRoute = str_contains($indexContents, "/constancias/toma-posesion/nueva");
$indexHasPostRoute = str_contains($indexContents, "\$app->post('/constancias/toma-posesion'");
$controllerHasMethod = str_contains($controllerContents, 'function newTomaPosesion');

$rows = [
    'Este archivo está en' => $publicDir,
    'Raíz de la app detectada' => $appRoot,
    'DOCUMENT_ROOT' => (string) ($_SERVER['DOCUMENT_ROOT'] ?? ''),
    'SCRIPT_FILENAME' => (string) ($_SERVER['SCRIPT_FILENAME'] ?? ''),
    'SCRIPT_NAME' => $scriptName,
    'REQUEST_URI' => $requestUri,
    'Path que usaría el router' => $path,
    'APP_BASE_PATH en .env' => $envBasePath !== '' ? $envBasePath : '(vacío o ausente)',
    'APP_DEBUG en .env' => $envDebug !== '' ? $envDebug : '(vacío o ausente)',
    'index.php existe' => is_file($indexFile) ? 'sí — ' . date('Y-m-d H:i:s', (int) filemtime($indexFile)) : 'NO',
    'index.php tiene GET /constancias/toma-posesion/nueva' => $indexHasGetRoute ? 'SÍ' : 'NO — este es el 404',
    'index.php tiene POST /constancias/toma-posesion' => $indexHasPostRoute ? 'SÍ' : 'NO',
    'ConstanciaController.php existe' => is_file($controllerFile) ? 'sí — ' . date('Y-m-d H:i:s', (int) filemtime($controllerFile)) : 'NO',
    'ConstanciaController tiene newTomaPosesion()' => $controllerHasMethod ? 'SÍ' : 'NO',
    'toma_posesion_form.php existe' => is_file($viewFile) ? 'sí — ' . date('Y-m-d H:i:s', (int) filemtime($viewFile)) : 'NO',
];

$indexLines = [];
if ($indexContents !== '') {
    foreach (preg_split('/\R/', $indexContents) ?: [] as $number => $line) {
        if (stripos($line, 'toma') !== false || stripos($line, 'constancias/') !== false) {
            $indexLines[] = ($number + 1) . ': ' . $line;
        }
    }
}

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Debug rutas constancias</title>
    <style>
        body { font-family: sans-serif; margin: 24px; color: #222; }
        table { border-collapse: collapse; width: 100%; max-width: 1100px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { width: 40%; background: #f3f3f3; }
        .ok { color: #0a7a28; font-weight: 700; }
        .bad { color: #b00020; font-weight: 700; }
        pre { background: #f6f6f6; padding: 12px; overflow: auto; max-width: 1100px; }
    </style>
</head>
<body>
    <h1>Debug de rutas</h1>
    <p>Si ves esta pantalla, este archivo está en el document root correcto. Borrarlo cuando termines.</p>
    <table>
        <?php foreach ($rows as $label => $value) : ?>
            <?php
            $class = '';
            $text = (string) $value;
            if (str_starts_with($text, 'SÍ') || $text === 'sí' || str_starts_with($text, 'sí —')) {
                $class = 'ok';
            } elseif (str_starts_with($text, 'NO')) {
                $class = 'bad';
            }
            ?>
            <tr>
                <th><?= h($label) ?></th>
                <td class="<?= h($class) ?>"><?= h($value) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Líneas de index.php que mencionan constancias/toma</h2>
    <pre><?= $indexLines === [] ? 'No encontré líneas (index.php ausente o sin esas rutas).' : h(implode("\n", $indexLines)) ?></pre>
</body>
</html>
