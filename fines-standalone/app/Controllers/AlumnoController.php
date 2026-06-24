<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Integrations\ProgramaFines\AlumnoNoExisteException;
use FinesApp\Integrations\ProgramaFines\ProgramaFinesClient;
use FinesApp\Integrations\ProgramaFines\ProgramaFinesMapper;
use FinesApp\Integrations\ProgramaFines\ProgramaFinesSession;
use FinesApp\Repositories\AlumnoComisionRepository;
use FinesApp\Repositories\AlumnoRepository;
use FinesApp\Repositories\CalificacionRepository;
use FinesApp\Repositories\DetallePersonaRepository;
use FinesApp\Repositories\PersonaRepository;
use FinesApp\Repositories\PlanRepository;

final class AlumnoController extends Controller
{
    public function show(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $personaId = (string) ($vars['id'] ?? '');

        $personaRepository = new PersonaRepository($this->pdo);
        $alumnoRepository = new AlumnoRepository($this->pdo);
        $persona = $personaRepository->find($personaId);

        if ($persona === null) {
            throw new \RuntimeException('No se encontro la persona solicitada.');
        }

        $alumno = $alumnoRepository->byPersona($personaId);
        $alumnoId = $alumno['id'] ?? null;
        $calificacionRepository = new CalificacionRepository($this->pdo);
        $alumnoComisionRepository = new AlumnoComisionRepository($this->pdo);
        $tienePlan = $alumnoId && !empty($alumno['plan']);
        $programaFines = [
            'connected' => (new ProgramaFinesSession())->connected(),
            'exists' => false,
            'remote' => null,
            'differences' => [],
            'error' => null,
        ];
        if ($programaFines['connected'] && trim((string) ($persona['numero_documento'] ?? '')) !== '') {
            try {
                $remote = $this->programaFinesClient()->student((string) $persona['numero_documento']);
                $programaFines['exists'] = true;
                $programaFines['remote'] = $remote;
                $programaFines['differences'] = ProgramaFinesMapper::differences($persona, $remote);
            } catch (AlumnoNoExisteException) {
                $programaFines['exists'] = false;
            } catch (\Throwable $throwable) {
                $programaFines['error'] = $throwable->getMessage();
            }
        }

        $this->view->render('alumnos/show', [
            'title' => 'Alumno',
            'persona' => $persona,
            'alumno' => $alumno,
            'planes' => (new PlanRepository($this->pdo))->all(),
            'comisiones' => $alumnoId ? $alumnoComisionRepository->byAlumno((string) $alumnoId) : [],
            'ultimaComision' => $alumnoId ? $alumnoComisionRepository->latestByAlumno((string) $alumnoId) : null,
            'estadosComision' => $alumnoId ? $alumnoComisionRepository->estados() : [],
            'calificacionesPlan' => $tienePlan ? $calificacionRepository->byAlumnoPlanTramo(
                (string) $alumnoId,
                (string) $alumno['plan'],
                $calificacionRepository->tramoIngresoShort($alumno),
            ) : [],
            'calificacionesOtroPlan' => $tienePlan ? $calificacionRepository->aprobadasByAlumnoNotInPlan(
                (string) $alumnoId,
                (string) $alumno['plan'],
            ) : [],
            'detalles' => (new DetallePersonaRepository($this->pdo))->byPersona($personaId),
            'notice' => flash('notice'),
            'error' => flash('error'),
            'programaFines' => $programaFines,
        ]);
    }

    public function actualizarProgramaFines(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $personaId = (string) ($vars['id'] ?? '');

        try {
            $persona = (new PersonaRepository($this->pdo))->find($personaId);
            if ($persona === null) {
                throw new \RuntimeException('No se encontró la persona.');
            }

            $client = $this->programaFinesClient();
            $client->student((string) $persona['numero_documento']);
            $client->updateStudent(ProgramaFinesMapper::localPersona(
                $persona,
                max(1, (int) $this->config->string('PROGRAMAFINES_PERIOD', '6')),
            ));
            Session::flash('notice', 'Datos del alumno actualizados en ProgramaFines.');
        } catch (AlumnoNoExisteException) {
            Session::flash('error', 'El alumno no existe en ProgramaFines. Agregalo desde una comisión con PFID.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function updatePersona(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        try {
            (new PersonaRepository($this->pdo))->update($personaId, [
                'nombres' => $this->required($request, 'nombres'),
                'apellidos' => $this->nullableText($request->input('apellidos')),
                ...$this->personaIdentificationData($request),
                'telefono' => $this->nullableText($request->input('telefono')),
                'codigo_area' => $this->nullableText($request->input('codigo_area')),
                'email' => $this->nullableText($request->input('email')),
                'email_abc' => $this->nullableText($request->input('email_abc')),
                'lugar_nacimiento' => $this->nullableText($request->input('lugar_nacimiento')),
                'nacionalidad' => $this->nullableText($request->input('nacionalidad')),
                'descripcion_domicilio' => $this->nullableText($request->input('descripcion_domicilio')),
                'departamento' => $this->nullableText($request->input('departamento')),
                'localidad' => $this->nullableText($request->input('localidad')),
                'partido' => $this->nullableText($request->input('partido')),
            ]);

            Session::flash('notice', 'Datos de persona guardados.');
        } catch (\InvalidArgumentException $exception) {
            Session::flash('error', $exception->getMessage());
            Response::redirect(url("/personas/{$personaId}/alumno"));
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                Session::flash('error', 'No se pudo guardar: DNI, CUIL o Email ABC ya pertenece a otra persona.');
                Response::redirect(url("/personas/{$personaId}/alumno"));
            }

            throw $exception;
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function saveAlumno(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        (new AlumnoRepository($this->pdo))->save($personaId, $request->input('alumno_id'), [
            'plan' => $request->input('plan'),
            'anio_ingreso' => $request->input('anio_ingreso'),
            'semestre_ingreso' => $this->nullableInt($request->input('semestre_ingreso')),
            'fecha_titulacion' => $request->input('fecha_titulacion') ?: null,
            'observaciones' => $request->input('observaciones'),
            'confirmado_direccion' => $request->checkbox('confirmado_direccion'),
        ]);

        Session::flash('notice', 'Datos de alumno guardados.');
        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function sincronizarCalificaciones(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        $alumno = (new AlumnoRepository($this->pdo))->byPersona($personaId);
        if ($alumno === null) {
            Session::flash('error', 'No hay registro de alumno para sincronizar calificaciones.');
            Response::redirect(url("/personas/{$personaId}/alumno"));
        }

        try {
            $result = (new CalificacionRepository($this->pdo))->sincronizarByAlumno($alumno);
            Session::flash(
                'notice',
                sprintf(
                    'Calificaciones sincronizadas. Se eliminaron %d desaprobadas y se crearon %d faltantes del plan.',
                    $result['deleted'],
                    $result['inserted'],
                ),
            );
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function saveCalificaciones(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        $alumnoId = $this->required($request, 'alumno_id');

        try {
            (new CalificacionRepository($this->pdo))->updateEditableFields($alumnoId, $this->calificacionRows($request));
            Session::flash('notice', 'Calificaciones guardadas.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function saveComisiones(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        $alumnoId = $this->required($request, 'alumno_id');

        try {
            (new AlumnoComisionRepository($this->pdo))->saveForAlumno($alumnoId, $this->comisionRows($request));
            Session::flash('notice', 'Comisiones guardadas.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function deleteComision(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        $alumnoId = $this->required($request, 'alumno_id');
        $alumnoComisionId = $this->required($request, 'alumno_comision_delete_id');

        try {
            (new AlumnoComisionRepository($this->pdo))->deleteForAlumno($alumnoId, $alumnoComisionId);
            Session::flash('notice', 'Comision eliminada del alumno.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function searchComisiones(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        Response::json((new AlumnoComisionRepository($this->pdo))->search((string) $request->query('q', ''), 10));
    }

    public function asociarCurso(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $curso = (new CalificacionRepository($this->pdo))->findCursoByAlumnoDisposicion(
            (string) $request->query('alumno', ''),
            (string) $request->query('disposicion', ''),
        );

        if ($curso === null) {
            Response::json(['message' => 'No se encontro un curso para las comisiones del alumno y esta disposicion.'], 404);
        }

        Response::json($curso);
    }

    private function required(Request $request, string $key): string
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException("El campo {$key} es obligatorio.");
        }

        return $value;
    }

    private function nullableInt(?string $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }

    private function nullableText(?string $value): ?string
    {
        return $value === null || $value === '' ? null : $value;
    }

    private function comisionRows(Request $request): array
    {
        $rows = [];
        $ids = $request->arrayInput('alumno_comision_id');
        $comisiones = $request->arrayInput('comision_ref');
        $estados = $request->arrayInput('estado');
        $activos = $request->arrayInput('activo');

        foreach ($ids as $index => $id) {
            if ((string) $id === '') {
                continue;
            }

            $rows[] = [
                'id' => (string) $id,
                'comision_ref' => (string) ($comisiones[$index] ?? ''),
                'estado' => (string) ($estados[$index] ?? ''),
                'activo' => isset($activos[$index]) ? 1 : 0,
            ];
        }

        $newComisionRef = $request->input('new_comision_ref');
        if ($newComisionRef !== null && $newComisionRef !== '') {
            $rows[] = [
                'id' => null,
                'comision_ref' => $newComisionRef,
                'estado' => $request->input('new_estado'),
                'activo' => $request->checkbox('new_activo'),
            ];
        }

        return $rows;
    }

    private function calificacionRows(Request $request): array
    {
        $rows = [];
        $ids = $request->arrayInput('calificacion_id');
        $notasFinales = $request->arrayInput('nota_final');
        $crecs = $request->arrayInput('crec');
        $cursos = $request->arrayInput('curso');
        $observaciones = $request->arrayInput('observaciones_calificacion');

        foreach ($ids as $index => $id) {
            if ((string) $id === '') {
                continue;
            }

            $rows[] = [
                'id' => (string) $id,
                'nota_final' => (string) ($notasFinales[$index] ?? ''),
                'crec' => (string) ($crecs[$index] ?? ''),
                'curso' => (string) ($cursos[$index] ?? ''),
                'observaciones' => (string) ($observaciones[$index] ?? ''),
            ];
        }

        return $rows;
    }

    private function programaFinesClient(): ProgramaFinesClient
    {
        $sessionId = (new ProgramaFinesSession())->id();
        if ($sessionId === null) {
            throw new \RuntimeException('Primero conectá una sesión desde la pantalla ProgramaFines.');
        }

        return new ProgramaFinesClient($sessionId);
    }
}
