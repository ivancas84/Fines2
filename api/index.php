<?php

require __DIR__ . '/src/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$parts = explode('/', $path);

if (empty($parts[PATH_START_API]) || $parts[PATH_START_API] !== 'api') {
    error_json('Not found', 404);
}

$resource = $parts[PATH_START_API+1] ?? '';

$db = \App\Context::getFinesDb();

if(in_array($resource, array_keys($db->entitiesMetadata))){
    $dataProvider = $db->CreateDataProvider();

    $id = $parts[PATH_START_API+2] ?? null;

    if ($id === null) {
        $rawEntities = $dataProvider->fetchAllJoinByParams($resource);

        send_json($rawEntities);
    }

    // GET /api/personas/xxxx-xxxx-xxxx
    $row = $dataProvider->fetchByUnique($resource, ['id' => $id]);

    if (!$row) {
        error_json($resource . ' sin encontrar', 404);
    }


    send_json($row);
        
} else { 
    error_json('Resource not found', 404);
}