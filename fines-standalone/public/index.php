<?php

declare(strict_types=1);

use FinesApp\Controllers\AlumnoController;
use FinesApp\Controllers\AuthController;
use FinesApp\Controllers\ComisionController;
use FinesApp\Controllers\CursoController;
use FinesApp\Controllers\DashboardController;
use FinesApp\Controllers\DocenteController;
use FinesApp\Controllers\InformeController;
use FinesApp\Controllers\PersonaController;
use FinesApp\Controllers\ProgramaFinesController;
use FinesApp\Controllers\SedeController;
use FinesApp\Core\App;
use FinesApp\Core\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = App::boot(dirname(__DIR__));
$request = Request::capture();
$app->get('/', [DashboardController::class, 'index']);
$app->get('/login', [AuthController::class, 'showLogin']);
$app->get('/login/google', [AuthController::class, 'redirectToGoogle']);
$app->get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
$app->post('/logout', [AuthController::class, 'logout']);
$app->get('/programafines', [ProgramaFinesController::class, 'edit']);
$app->post('/programafines', [ProgramaFinesController::class, 'connect']);
$app->post('/programafines/desconectar', [ProgramaFinesController::class, 'disconnect']);
$app->get('/informes', [InformeController::class, 'index']);
$app->get('/informes/egresados', [InformeController::class, 'egresados']);

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
$app->get('/cursos', [CursoController::class, 'index']);
$app->get('/cursos/{id}/planilla', [CursoController::class, 'planilla']);
$app->post('/cursos/{id}/planilla/consultar', [CursoController::class, 'planillaConsultar']);
$app->post('/cursos/{id}/planilla/guardar', [CursoController::class, 'planillaGuardar']);
$app->post('/cursos/{id}/planilla/entregada', [CursoController::class, 'marcarPlanillaEntregada']);
$app->get('/cursos/asociar', [AlumnoController::class, 'asociarCurso']);
$app->get('/comisiones', [ComisionController::class, 'index']);
$app->get('/comisiones/{id}/alumnos', [ComisionController::class, 'alumnos']);
$app->get('/sedes', [SedeController::class, 'index']);
$app->post('/comisiones/{id}/programafines/enviar', [ComisionController::class, 'enviarAlumnoProgramaFines']);
$app->post('/comisiones/{id}/programafines/sincronizar', [ComisionController::class, 'sincronizarProgramaFines']);
$app->post('/comisiones/{id}/programafines/importar', [ComisionController::class, 'importarAlumnoProgramaFines']);
$app->post('/comisiones/{id}/programafines/quitar', [ComisionController::class, 'quitarAlumnoProgramaFines']);
$app->post('/personas/{id}/programafines/actualizar', [AlumnoController::class, 'actualizarProgramaFines']);

$app->dispatch($request);
