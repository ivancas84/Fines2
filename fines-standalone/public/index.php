<?php

declare(strict_types=1);

use FinesApp\Controllers\AlumnoController;
use FinesApp\Controllers\AuthController;
use FinesApp\Controllers\ComisionController;
use FinesApp\Controllers\ConstanciaController;
use FinesApp\Controllers\DashboardController;
use FinesApp\Controllers\EstablecimientoController;
use FinesApp\Controllers\PersonaController;
use FinesApp\Core\App;
use FinesApp\Core\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = App::boot(dirname(__DIR__));
$request = Request::capture();
$app->get('/', [DashboardController::class, 'index']);
$app->get('/login', [AuthController::class, 'showLogin']);
$app->post('/login', [AuthController::class, 'login']);
$app->post('/logout', [AuthController::class, 'logout']);
$app->get('/establecimiento', [EstablecimientoController::class, 'edit']);
$app->post('/establecimiento', [EstablecimientoController::class, 'update']);

$app->get('/personas', [PersonaController::class, 'index']);
$app->get('/personas/{id}/alumno', [AlumnoController::class, 'show']);
$app->post('/personas/{id}', [AlumnoController::class, 'updatePersona']);
$app->post('/personas/{id}/alumno', [AlumnoController::class, 'saveAlumno']);
$app->post('/personas/{id}/alumno/calificaciones/sincronizar', [AlumnoController::class, 'sincronizarCalificaciones']);
$app->post('/personas/{id}/alumno/calificaciones', [AlumnoController::class, 'saveCalificaciones']);
$app->post('/personas/{id}/alumno/comisiones', [AlumnoController::class, 'saveComisiones']);
$app->get('/comisiones/buscar', [AlumnoController::class, 'searchComisiones']);
$app->get('/cursos/buscar', [AlumnoController::class, 'searchCursos']);
$app->get('/personas/{id}/alumno/constancias/alumno-regular/nueva', [ConstanciaController::class, 'newAlumnoRegular']);
$app->post('/personas/{id}/alumno/constancias/alumno-regular', [ConstanciaController::class, 'createAlumnoRegular']);
$app->get('/validar-constancia', [ConstanciaController::class, 'validateConstancia']);
$app->get('/validar-constancia/descargar', [ConstanciaController::class, 'download']);

$app->get('/comisiones', [ComisionController::class, 'index']);

$app->dispatch($request);
