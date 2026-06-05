<?php

declare(strict_types=1);

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
$app->post('/logout', [AuthController::class, 'logout']);

$app->get('/constancias/alumno-regular/nueva', [ConstanciaController::class, 'newAlumnoRegular']);
$app->post('/constancias/alumno-regular', [ConstanciaController::class, 'createAlumnoRegular']);
$app->get('/establecimiento', [EstablecimientoController::class, 'edit']);
$app->post('/establecimiento', [EstablecimientoController::class, 'update']);

$app->get('/validar-constancia', [ConstanciaController::class, 'validateConstancia']);
$app->get('/validar-constancia/descargar', [ConstanciaController::class, 'download']);

$app->dispatch($request);
