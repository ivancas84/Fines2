<?php

use SqlOrganize\Sql\Entity;
use SqlOrganize\Sql\ModifyQueries;

require __DIR__ . '/src/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path   = trim($path, '/');
$parts  = explode('/', $path);
$db  = \App\Context::getFinesDb();


if (empty($parts[PATH_START_API]) || $parts[PATH_START_API] !== 'api') {
    error_json('Not found', 404);
}

$resource = $parts[PATH_START_API + 1] ?? '';
$id       = $parts[PATH_START_API + 2] ?? null;

if (!in_array($resource, array_keys($db->entitiesMetadata))) {
    error_json('Resource not found', 404);
}

$dataProvider = $db->CreateDataProvider();

// ────────────────────────────────────────────────
//   Helper: read JSON body safely
// ────────────────────────────────────────────────
function get_json_body() {
    $input = file_get_contents('php://input');
    $data  = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_json('Invalid JSON: ' . json_last_error_msg(), 400);
    }
    return $data ?: [];
}

// ────────────────────────────────────────────────
//   Routing by method
// ────────────────────────────────────────────────

if ($method === 'GET') {

    if ($id === null) {
        // GET /api/personas           → list
        $items = $dataProvider->fetchAllJoinByParams($resource);
        send_json($items);
    } else {
        // GET /api/personas/xxxx      → one row
        $row = $dataProvider->fetchByUnique($resource, ['id' => $id]);
        if (!$row) {
            error_json("$resource no encontrado", 404);
        }
        send_json($row);
    }

} 
elseif ($method === 'POST' && $id === null) {

    // POST /api/personas          → create
    $data = get_json_body();

    if (empty($data)) {
        error_json('Cuerpo vacío o inválido', 400);
    }

    try {
        /** @var Entity */ $entity = $db->GetEntity($resource);
        $entity->ssetFromArray($data);
        $entity->reset();
        throw new Exception(print_r($entity->toArray(),true));
        if(!$entity->check()) throw new Exception($entity->getLogging()->__toString());
        /** @var ModifyQueries */ $modifyQueries = $db->CreateModifyQueries();
        $entity->persist($modifyQueries);
        $modifyQueries->process();
        $created = $dataProvider->fetchByUnique($resource, ['id' => $newId]);
        header('Location: /api/' . $resource . '/' . $newId, true, 201);
        send_json($created, 201);
    } catch (Exception $e) {
        error_json('Error al crear: ' . $e->getMessage(), 422);
    }

} 
elseif (in_array($method, ['PUT', 'PATCH']) && $id !== null) {

    // PUT   /api/personas/xxxx    → replace completely
    // PATCH /api/personas/xxxx    → partial update
    $data = get_json_body();

    if (empty($data)) {
        error_json('Cuerpo vacío o inválido', 400);
    }

    $row = $dataProvider->fetchByUnique($resource, ['id' => $id]);
    if (!$row) {
        error_json("$resource no encontrado", 404);
    }

    try {
        if ($method === 'PUT') {
            // full replace — usually requires all required fields
            //$dataProvider->update($resource, $data, ['id' => $id]);
        } else {
            // PATCH — only update sent fields
            //$dataProvider->partialUpdate($resource, $data, ['id' => $id]);
            // ^^^ you may need to implement or rename this method
        }

        //$updated = $dataProvider->fetchByUnique($resource, ['id' => $id]);
        //send_json($updated);   // or send_json([], 204) if you prefer no content
    } catch (Exception $e) {
        error_json('Error al actualizar: ' . $e->getMessage(), 422);
    }

} 
else {
    // 405 Method Not Allowed for all other combinations
    header('Allow: GET, POST, PUT, PATCH');
    error_json('Método no permitido', 405);
}