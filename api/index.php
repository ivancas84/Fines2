<?php

require __DIR__ . '/src/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$parts = explode('/', $path);

if (empty($parts[PATH_START_API]) || $parts[PATH_START_API] !== 'api') {
    error_json('Not found', 404);
}

$resource = $parts[PATH_START_API+1] ?? '';
$id = $parts[PATH_START_API+2] ?? null;

switch ($resource) {
    case 'personas':
        require __DIR__ . '/src/handlers.php';
        handle_personas($id, $dataProvider);
        break;

    case 'comisiones':
        require __DIR__ . '/src/handlers.php';
        handle_comisiones($id, $dataProvider);
        break;

    default:
        error_json('Resource not found', 404);
}