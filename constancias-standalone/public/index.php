<?php

declare(strict_types=1);

use ConstanciasApp\Controllers\ApiTomaPosesionController;
use ConstanciasApp\Controllers\AuthController;
use ConstanciasApp\Controllers\ConstanciaController;
use ConstanciasApp\Controllers\DashboardController;
use ConstanciasApp\Controllers\EstablecimientoController;
use ConstanciasApp\Core\App;
use ConstanciasApp\Core\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = App::boot(dirname(__DIR__));
$request = Request::capture();

$app->get('/', [DashboardController::class, 'index']);
$app->get('/login', [AuthController::class, 'showLogin']);
$app->post('/login', [AuthController::class, 'login']);
$app->get('/login/google', [AuthController::class, 'redirectToGoogle']);
$app->get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
$app->post('/logout', [AuthController::class, 'logout']);

$app->get('/constancias', [ConstanciaController::class, 'index']);
$app->get('/constancias/alumno-regular/nueva', [ConstanciaController::class, 'newAlumnoRegular']);
$app->post('/constancias/alumno-regular', [ConstanciaController::class, 'createAlumnoRegular']);
$app->get('/constancias/titulo-tramite/nueva', [ConstanciaController::class, 'newTituloTramite']);
$app->post('/constancias/titulo-tramite', [ConstanciaController::class, 'createTituloTramite']);
$app->get('/constancias/vacante/nueva', [ConstanciaController::class, 'newVacante']);
$app->post('/constancias/vacante', [ConstanciaController::class, 'createVacante']);
$app->get('/constancias/pase/nueva', [ConstanciaController::class, 'newPase']);
$app->post('/constancias/pase', [ConstanciaController::class, 'createPase']);
$app->get('/constancias/general/nueva', [ConstanciaController::class, 'newGeneral']);
$app->post('/constancias/general', [ConstanciaController::class, 'createGeneral']);
$app->get('/constancias/toma-posesion/nueva', [ConstanciaController::class, 'newTomaPosesion']);
$app->post('/constancias/toma-posesion', [ConstanciaController::class, 'createTomaPosesion']);
$app->get('/establecimiento', [EstablecimientoController::class, 'edit']);
$app->get('/establecimiento/imagen/{tipo}', [EstablecimientoController::class, 'image']);
$app->post('/establecimiento', [EstablecimientoController::class, 'update']);

$app->get('/validar-constancia', [ConstanciaController::class, 'validateConstancia']);
$app->get('/validar-constancia/descargar', [ConstanciaController::class, 'download']);

// API interna (fines-standalone): generar toma de posesión + PDF (+ email opcional)
$app->post('/api/toma-posesion', [ApiTomaPosesionController::class, 'create']);

$app->dispatch($request);
