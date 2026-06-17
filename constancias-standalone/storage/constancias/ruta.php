<?php

declare(strict_types=1);

use ConstanciasApp\Controllers\AuthController;
use ConstanciasApp\Controllers\ConstanciaController;
use ConstanciasApp\Controllers\DashboardController;
use ConstanciasApp\Controllers\EstablecimientoController;
use ConstanciasApp\Core\App;
use ConstanciasApp\Core\Request;

require dirname(__DIR__) . '/constancias-standalone/vendor/autoload.php';

$app = App::boot(dirname(__DIR__) . '/constancias-standalone'); 
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
$app->get('/establecimiento', [EstablecimientoController::class, 'edit']);
$app->get('/establecimiento/imagen/{tipo}', [EstablecimientoController::class, 'image']);
$app->post('/establecimiento', [EstablecimientoController::class, 'update']);

$app->get('/validar-constancia', [ConstanciaController::class, 'validateConstancia']);
$app->get('/validar-constancia/descargar', [ConstanciaController::class, 'download']);

$app->dispatch($request);
