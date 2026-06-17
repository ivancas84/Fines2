<?php

declare(strict_types=1);

use FinesApp\Controllers\AlumnoController;
use FinesApp\Controllers\AuthController;
use FinesApp\Controllers\ComisionController;
use FinesApp\Controllers\DashboardController;
use FinesApp\Controllers\DocenteController;
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
$app->get('/login/google', [AuthController::class, 'redirectToGoogle']);
$app->get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
$app->post('/logout', [AuthController::class, 'logout']);
$app->get('/establecimiento', [EstablecimientoController::class, 'edit']);
$app->post('/establecimiento', [EstablecimientoController::class, 'update']);

$app->get('/personas', [PersonaController::class, 'index']);
$app->get('/personas/{id}/alumno', [AlumnoController::class, 'show']);
$app->get('/personas/{id}/docente', [DocenteController::class, 'show']);
$app->post('/personas/{id}', [AlumnoController::class, 'updatePersona']);
$app->post('/personas/{id}/docente', [DocenteController::class, 'updatePersona']);
$app->post('/personas/{id}/docente/tomas', [DocenteController::class, 'saveTomas']);
$app->post('/personas/{id}/alumno', [AlumnoController::class, 'saveAlumno']);
$app->post('/personas/{id}/alumno/calificaciones/sincronizar', [AlumnoController::class, 'sincronizarCalificaciones']);
$app->post('/personas/{id}/alumno/calificaciones', [AlumnoController::class, 'saveCalificaciones']);
$app->post('/personas/{id}/alumno/comisiones', [AlumnoController::class, 'saveComisiones']);
$app->post('/personas/{id}/alumno/comisiones/eliminar', [AlumnoController::class, 'deleteComision']);
$app->get('/comisiones/buscar', [AlumnoController::class, 'searchComisiones']);
$app->get('/cursos/asociar', [AlumnoController::class, 'asociarCurso']);
$app->get('/comisiones', [ComisionController::class, 'index']);

$app->dispatch($request);
