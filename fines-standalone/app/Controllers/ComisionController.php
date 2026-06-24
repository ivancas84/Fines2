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
use FinesApp\Repositories\ComisionRepository;

final class ComisionController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $repository = new ComisionRepository($this->pdo);
        $calendarios = $repository->calendarios();
        $selectedCalendario = (string) $request->query('calendario', '');
        $soloAutorizadas = $selectedCalendario === '' || $request->query('autorizada') !== null;
        $sort = (string) $request->query('sort', 'pfid');
        $order = (string) $request->query('order', 'asc');

        if (!in_array($sort, ['nombre', 'pfid', 'planificacion', 'apertura', 'turno'], true)) {
            $sort = 'pfid';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }
        if ($selectedCalendario === '' && $calendarios !== []) {
            $selectedCalendario = (string) $calendarios[0]['id'];
        }

        $comisiones = $selectedCalendario !== ''
            ? $repository->comisiones($selectedCalendario, $soloAutorizadas, $sort, $order)
            : [];

        $this->view->render('comisiones/index', [
            'title' => 'Comisiones',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'soloAutorizadas' => $soloAutorizadas,
            'sort' => $sort,
            'order' => $order,
            'comisiones' => $comisiones,
        ]);
    }

    public function alumnos(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $comisionId = trim((string) ($vars['id'] ?? ''));
        $repository = new ComisionRepository($this->pdo);
        $comision = $comisionId !== '' ? $repository->byId($comisionId) : null;

        if ($comision === null) {
            $this->view->render('errors/404', ['title' => 'Comision no encontrada'], 404);
            return;
        }

        $alumnos = $repository->alumnos($comisionId);
        $programaFines = [
            'connected' => false,
            'periodo' => $this->periodoProgramaFines(),
            'students' => [],
            'error' => null,
            'local_only' => [],
            'remote_only' => [],
            'both' => [],
        ];

        $connection = new ProgramaFinesSession();
        if ($connection->connected() && trim((string) ($comision['pfid'] ?? '')) !== '') {
            $programaFines['connected'] = true;
            try {
                $programaFines['students'] = $this->programaFinesClient()->students(
                    (string) $comision['pfid'],
                    $programaFines['periodo'],
                );
                $comparison = $this->compareStudents($alumnos, $programaFines['students']);
                $programaFines = array_merge($programaFines, $comparison);
            } catch (\Throwable $throwable) {
                $programaFines['error'] = $throwable->getMessage();
            }
        }

        $this->view->render('comisiones/alumnos', [
            'title' => 'Alumnos de la comision',
            'comision' => $comision,
            'alumnos' => $alumnos,
            'programaFines' => $programaFines,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function enviarAlumnoProgramaFines(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $repository = new ComisionRepository($this->pdo);
            $student = $repository->alumnoComision($comisionId, (string) $request->input('alumno_comision_id', ''));
            if ($student === null) {
                throw new \RuntimeException('No se encontró el alumno dentro de la comisión.');
            }
            $this->syncStudent($student);
            Session::flash('notice', 'Alumno enviado correctamente a ProgramaFines.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
    }

    public function sincronizarProgramaFines(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        $repository = new ComisionRepository($this->pdo);
        $comision = $repository->byId($comisionId);
        if ($comision === null) {
            Session::flash('error', 'La comisión no existe.');
            Response::redirect(url('/comisiones'));
        }
        if (trim((string) ($comision['pfid'] ?? '')) === '') {
            Session::flash('error', 'La comisión no tiene PFID configurado.');
            Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
        }

        $ok = 0;
        $errors = [];
        try {
            $client = $this->programaFinesClient();
            $currentDnis = array_map(
                static fn (array $remote): string => ProgramaFinesMapper::digits((string) ($remote['numero_documento'] ?? '')),
                $client->students((string) ($comision['pfid'] ?? ''), $this->periodoProgramaFines()),
            );
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
        }

        foreach ($repository->alumnos($comisionId) as $student) {
            $student['pfid'] = $comision['pfid'] ?? '';
            try {
                $this->syncStudent($student, $currentDnis, $client);
                $currentDnis[] = ProgramaFinesMapper::digits((string) ($student['numero_documento'] ?? ''));
                $currentDnis = array_values(array_unique($currentDnis));
                $ok++;
            } catch (\Throwable $throwable) {
                $label = trim((string) ($student['apellidos'] ?? '') . ' ' . (string) ($student['nombres'] ?? ''));
                $errors[] = ($label !== '' ? $label : (string) ($student['numero_documento'] ?? '')) . ': ' . $throwable->getMessage();
            }
        }

        if ($errors === []) {
            Session::flash('notice', "Sincronización completa: {$ok} alumnos procesados.");
        } else {
            Session::flash('error', "Se procesaron {$ok} alumnos. Errores: " . implode(' | ', $errors));
        }
        Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
    }

    public function importarAlumnoProgramaFines(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $dni = (string) $request->input('dni', '');
            $remote = $this->programaFinesClient()->student($dni);
            $remote['dni'] = $remote['dni'] ?: ProgramaFinesMapper::digits($dni);
            $result = (new ComisionRepository($this->pdo))->importProgramaFinesStudent($comisionId, $remote);
            Session::flash(
                'notice',
                $result['relation_created']
                    ? 'Alumno importado y agregado a la comisión local.'
                    : 'El alumno ya pertenecía a la comisión local; se completaron datos faltantes.',
            );
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
    }

    public function quitarAlumnoProgramaFines(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $comision = (new ComisionRepository($this->pdo))->byId($comisionId);
            if ($comision === null || trim((string) ($comision['pfid'] ?? '')) === '') {
                throw new \RuntimeException('La comisión no tiene PFID configurado.');
            }
            $this->programaFinesClient()->removeStudent(
                (string) $comision['pfid'],
                $this->periodoProgramaFines(),
                (string) $request->input('dni', ''),
            );
            Session::flash('notice', 'Alumno quitado de la comisión de ProgramaFines.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}/alumnos"));
    }

    private function syncStudent(
        array $student,
        ?array $currentDnis = null,
        ?ProgramaFinesClient $client = null,
    ): void
    {
        $pfid = trim((string) ($student['pfid'] ?? ''));
        if ($pfid === '') {
            throw new \RuntimeException('La comisión no tiene PFID configurado.');
        }

        $client ??= $this->programaFinesClient();
        $dni = ProgramaFinesMapper::digits((string) ($student['numero_documento'] ?? ''));
        $data = ProgramaFinesMapper::localPersona($student, $this->periodoProgramaFines(), $pfid);
        $currentDnis ??= array_map(
            static fn (array $remote): string => ProgramaFinesMapper::digits((string) ($remote['numero_documento'] ?? '')),
            $client->students($pfid, $this->periodoProgramaFines()),
        );

        try {
            $client->student($dni);
            $client->updateStudent($data);
            if (!in_array($dni, $currentDnis, true)) {
                $client->transferStudent($dni, $pfid);
            }
        } catch (AlumnoNoExisteException) {
            $client->createStudent($data);
        }
    }

    private function programaFinesClient(): ProgramaFinesClient
    {
        $sessionId = (new ProgramaFinesSession())->id();
        if ($sessionId === null) {
            throw new \RuntimeException('Primero conectá una sesión desde la pantalla ProgramaFines.');
        }

        return new ProgramaFinesClient($sessionId);
    }

    private function periodoProgramaFines(): int
    {
        return max(1, (int) $this->config->string('PROGRAMAFINES_PERIOD', '6'));
    }

    private function compareStudents(array $local, array $remote): array
    {
        $localDocuments = [];
        foreach ($local as $student) {
            $dni = ProgramaFinesMapper::digits((string) ($student['numero_documento'] ?? ''));
            if ($dni !== '') {
                $localDocuments[$dni] = true;
            }
        }

        $remoteDocuments = [];
        foreach ($remote as $student) {
            $dni = ProgramaFinesMapper::digits((string) ($student['numero_documento'] ?? ''));
            if ($dni !== '') {
                $remoteDocuments[$dni] = true;
            }
        }

        return [
            'local_only' => array_values(array_diff(array_keys($localDocuments), array_keys($remoteDocuments))),
            'remote_only' => array_values(array_diff(array_keys($remoteDocuments), array_keys($localDocuments))),
            'both' => array_values(array_intersect(array_keys($localDocuments), array_keys($remoteDocuments))),
        ];
    }
}
