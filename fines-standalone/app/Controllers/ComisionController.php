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
use FinesApp\Repositories\TomaRepository;

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
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function generarSiguiente(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = trim((string) ($vars['id'] ?? ''));

        $returnCalendario = trim((string) $request->input('return_calendario', ''));
        $calendarioDestino = $this->nullableText($request->input('calendario_destino'));
        if ($calendarioDestino === null) {
            $calendarioDestino = $this->nullableText($this->config->string('CALENDARIO_ID_ACTUAL', ''));
        }

        try {
            $result = (new ComisionRepository($this->pdo))->generarSiguiente(
                $comisionId,
                $calendarioDestino,
            );

            if ($result['created']) {
                $msg = 'Comisión siguiente creada.';
            } else {
                $msg = 'La comisión ya tenía comisión siguiente asignada.';
            }
            if ($result['cursos_creados'] > 0) {
                $msg .= " Se crearon {$result['cursos_creados']} curso(s).";
            }
            Session::flash('notice', $msg);

            $redirectToAdmin = $request->input('redirect') === 'admin';
            if ($redirectToAdmin) {
                Response::redirect(url('/comisiones/' . rawurlencode($result['id'])));
            }

            $query = $returnCalendario !== ''
                ? '?calendario=' . rawurlencode($returnCalendario)
                : ($result['calendario_id'] !== ''
                    ? '?calendario=' . rawurlencode($result['calendario_id'])
                    : '');
            Response::redirect(url('/comisiones' . $query));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            $redirectToAdmin = $request->input('redirect') === 'admin';
            if ($redirectToAdmin && $comisionId !== '') {
                Response::redirect(url('/comisiones/' . rawurlencode($comisionId)));
            }
            $query = $returnCalendario !== ''
                ? '?calendario=' . rawurlencode($returnCalendario)
                : '';
            Response::redirect(url('/comisiones' . $query));
        }
    }

    public function transferirAlumnosActivos(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = trim((string) ($vars['id'] ?? ''));
        $returnCalendario = trim((string) $request->input('return_calendario', ''));
        $redirect = (string) $request->input('redirect', 'list');

        $siguienteId = null;
        try {
            $result = (new ComisionRepository($this->pdo))->transferirAlumnosActivos($comisionId);
            $siguienteId = $result['comision_siguiente_id'];
            $msg = "Transferencia a comisión siguiente: {$result['transferidos']} alumno(s) copiado(s)";
            $msg .= " de {$result['activos_origen']} activo(s)";
            if ($result['ya_en_siguiente'] > 0) {
                $msg .= " ({$result['ya_en_siguiente']} ya estaban en la siguiente)";
            }
            $msg .= '.';
            Session::flash('notice', $msg);
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        if ($redirect === 'siguiente' && $siguienteId !== null && $siguienteId !== '') {
            Response::redirect(url('/comisiones/' . rawurlencode($siguienteId) . '/alumnos'));
        }
        if ($redirect === 'admin') {
            Response::redirect(url('/comisiones/' . rawurlencode($comisionId)));
        }
        if ($redirect === 'alumnos' || $redirect === 'siguiente') {
            Response::redirect(url('/comisiones/' . rawurlencode($comisionId) . '/alumnos'));
        }

        $query = $returnCalendario !== ''
            ? '?calendario=' . rawurlencode($returnCalendario)
            : '';
        Response::redirect(url('/comisiones' . $query));
    }

    public function reactivarAlumnos(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = trim((string) ($vars['id'] ?? ''));
        $returnCalendario = trim((string) $request->input('return_calendario', ''));
        $redirect = (string) $request->input('redirect', 'list');
        $from = trim((string) $request->input('from', ''));

        try {
            $result = (new ComisionRepository($this->pdo))->reactivarAlumnos($comisionId);
            $tramo = $result['planificacion_anio'] . '°' . $result['planificacion_semestre'] . 'C';
            $msg = "Reactivar alumnos ({$tramo}): {$result['total']} evaluado(s)";
            $msg .= ", {$result['activados']} activado(s)";
            $msg .= ", {$result['desactivados']} desactivado(s)";
            if ($result['sin_cambio'] > 0) {
                $msg .= ", {$result['sin_cambio']} sin cambio";
            }
            $msg .= '. Criterio: ≥ 3 calificaciones aprobadas del mismo año/semestre de la planificación.';
            Session::flash('notice', $msg);
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        if ($redirect === 'rindex' && $comisionId !== '') {
            $query = $from !== '' ? '?from=' . rawurlencode($from) : '';
            Response::redirect(url('/comisiones/' . rawurlencode($comisionId) . '/rindex' . $query));
        }

        $query = $returnCalendario !== ''
            ? '?calendario=' . rawurlencode($returnCalendario)
            : '';
        Response::redirect(url('/comisiones' . $query));
    }

    public function rindex(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $comisionId = trim((string) ($vars['id'] ?? ''));
        $repository = new ComisionRepository($this->pdo);
        $rindex = $comisionId !== '' ? $repository->rindex($comisionId) : null;

        if ($rindex === null) {
            $this->view->render('errors/404', ['title' => 'Comisión no encontrada'], 404);
            return;
        }

        $this->view->render('comisiones/rindex', [
            'title' => 'Rindex comisión',
            'comision' => $rindex['comision'],
            'columnas' => $rindex['columnas'],
            'filas' => $rindex['filas'],
            'from' => (string) $request->query('from', ''),
            'notice' => flash('notice'),
            'error' => flash('error'),
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

    public function createForm(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $comisionRepository = new ComisionRepository($this->pdo);
        $calendarios = $comisionRepository->calendarios();
        $selectedCalendario = (string) $request->query('calendario', '');
        if ($selectedCalendario === '' && $calendarios !== []) {
            $selectedCalendario = (string) $calendarios[0]['id'];
        }

        $this->renderAdminForm(
            comision: [
                'id' => '',
                'pfid' => '',
                'turno' => '',
                'division' => '',
                'autorizada' => 0,
                'apertura' => 0,
                'publicada' => 0,
                'observaciones' => '',
                'calendario_id' => $selectedCalendario,
                'sede_id' => '',
                'modalidad_id' => '',
                'planificacion_id' => '',
                'comision_siguiente' => '',
                'sede_nombre' => '',
                'calendario_label' => '',
            ],
            isNew: true,
            title: 'Nueva comisión',
        );
    }

    public function create(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        try {
            $data = $this->comisionFormData($request);
            $result = (new ComisionRepository($this->pdo))->createAdmin($data);
            $extra = $result['cursos_creados'] > 0
                ? " Se crearon {$result['cursos_creados']} cursos de la planificación."
                : '';
            Session::flash('notice', 'Comisión creada.' . $extra);
            Response::redirect(url('/comisiones/' . rawurlencode($result['id'])));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url('/comisiones/nueva'));
        }
    }

    public function admin(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $comisionId = trim((string) ($vars['id'] ?? ''));
        $comisionRepository = new ComisionRepository($this->pdo);
        $comision = $comisionId !== '' ? $comisionRepository->byId($comisionId) : null;

        if ($comision === null) {
            $this->view->render('errors/404', ['title' => 'Comisión no encontrada'], 404);
            return;
        }

        $this->renderAdminForm(
            comision: $comision,
            isNew: false,
            title: 'Administrar comisión',
        );
    }

    public function saveComision(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $repository = new ComisionRepository($this->pdo);
            if ($repository->byId($comisionId) === null) {
                throw new \RuntimeException('La comisión no existe.');
            }

            $result = $repository->updateAdmin($comisionId, $this->comisionFormData($request));

            $extra = $result['cursos_creados'] > 0
                ? " Se crearon {$result['cursos_creados']} cursos de la planificación."
                : '';
            Session::flash('notice', 'Comisión guardada.' . $extra);
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}"));
    }

    public function deleteComision(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = trim((string) ($vars['id'] ?? ''));

        try {
            $result = (new ComisionRepository($this->pdo))->deleteIfAllowed($comisionId);
            $extra = $result['cursos_eliminados'] > 0
                ? " Se eliminaron {$result['cursos_eliminados']} curso(s)."
                : '';
            Session::flash('notice', 'Comisión eliminada.' . $extra);
            $calendarioQuery = $result['calendario_id'] !== ''
                ? '?calendario=' . rawurlencode($result['calendario_id'])
                : '';
            Response::redirect(url('/comisiones' . $calendarioQuery));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url('/comisiones/' . rawurlencode($comisionId)));
        }
    }

    /**
     * @param array<string, mixed> $comision
     */
    private function renderAdminForm(array $comision, bool $isNew, string $title): void
    {
        $comisionRepository = new ComisionRepository($this->pdo);
        $tomaRepository = new TomaRepository($this->pdo);
        $comisionId = (string) ($comision['id'] ?? '');

        $this->view->render('comisiones/admin', [
            'title' => $title,
            'isNew' => $isNew,
            'comision' => $comision,
            'calendarios' => $comisionRepository->calendarios(),
            'sedes' => $comisionRepository->sedesOptions(),
            'modalidades' => $comisionRepository->modalidadesOptions(),
            'planificaciones' => $comisionRepository->planificacionesOptions(),
            'disposiciones' => $isNew ? [] : $comisionRepository->disposicionesOptions(),
            'cursos' => $isNew || $comisionId === '' ? [] : $comisionRepository->cursosByComision($comisionId),
            'tomas' => $isNew || $comisionId === '' ? [] : $tomaRepository->byComision($comisionId),
            'estadosToma' => $isNew ? [] : $tomaRepository->estados(),
            'tiposMovimiento' => $isNew ? [] : $tomaRepository->tiposMovimiento(),
            'estadosContralor' => $isNew ? [] : $tomaRepository->estadosContralor(),
            'deleteBlockers' => $isNew || $comisionId === '' ? [] : $comisionRepository->deleteBlockers($comisionId),
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    /** @return array<string, mixed> */
    private function comisionFormData(Request $request): array
    {
        $calendario = trim((string) $request->input('calendario', ''));
        $sede = trim((string) $request->input('sede', ''));
        $modalidad = trim((string) $request->input('modalidad', ''));
        $division = trim((string) $request->input('division', ''));
        if ($calendario === '' || $sede === '' || $modalidad === '') {
            throw new \InvalidArgumentException('Calendario, sede y modalidad son obligatorios.');
        }
        if ($division === '') {
            $division = '-';
        }

        return [
            'calendario' => $calendario,
            'sede' => $sede,
            'modalidad' => $modalidad,
            'planificacion' => $this->nullableText($request->input('planificacion')),
            'turno' => $this->nullableText($request->input('turno')),
            'division' => $division,
            'pfid' => $this->nullableText($request->input('pfid')),
            'autorizada' => $request->checkbox('autorizada'),
            'apertura' => $request->checkbox('apertura'),
            'publicada' => $request->checkbox('publicada'),
            'observaciones' => $this->nullableText($request->input('observaciones')),
            'comision_siguiente' => $this->nullableText($request->input('comision_siguiente')),
        ];
    }

    public function saveCursos(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $repository = new ComisionRepository($this->pdo);
            $deleteId = trim((string) $request->input('delete_curso_id', ''));
            if ($deleteId !== '') {
                $repository->deleteCurso($comisionId, $deleteId);
                Session::flash('notice', 'Curso eliminado.');
            } else {
                $repository->updateCursos($comisionId, $this->cursoRows($request));
                Session::flash('notice', 'Cursos guardados.');
            }
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}"));
    }

    public function addCurso(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $disposicion = trim((string) $request->input('disposicion', ''));
            if ($disposicion === '') {
                throw new \InvalidArgumentException('Seleccioná una disposición.');
            }

            (new ComisionRepository($this->pdo))->addCurso($comisionId, [
                'disposicion' => $disposicion,
                'horas_catedra' => $request->input('horas_catedra', '0'),
                'descripcion_horario' => $request->input('descripcion_horario'),
            ]);
            Session::flash('notice', 'Curso agregado.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}"));
    }

    public function saveTomas(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            $repository = new TomaRepository($this->pdo);
            $deleteId = trim((string) $request->input('delete_toma_id', ''));
            if ($deleteId !== '') {
                $repository->deleteInComision($comisionId, $deleteId);
                Session::flash('notice', 'Toma eliminada.');
            } else {
                $repository->updateForComision($comisionId, $this->tomaRows($request));
                Session::flash('notice', 'Tomas guardadas.');
            }
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}"));
    }

    public function addToma(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $comisionId = (string) ($vars['id'] ?? '');

        try {
            (new TomaRepository($this->pdo))->addForComision($comisionId, [
                'fecha_toma' => $request->input('fecha_toma'),
                'curso' => $request->input('curso'),
                'dni_docente' => $request->input('dni_docente'),
                'estado' => $request->input('estado'),
                'tipo_movimiento' => $request->input('tipo_movimiento'),
                'estado_contralor' => $request->input('estado_contralor'),
            ]);
            Session::flash('notice', 'Toma agregada.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url("/comisiones/{$comisionId}"));
    }

    /** @return list<array{id: string, disposicion: mixed, horas_catedra: mixed, descripcion_horario: mixed}> */
    private function cursoRows(Request $request): array
    {
        $ids = $request->arrayInput('curso_id');
        $disposiciones = $request->arrayInput('disposicion');
        $horas = $request->arrayInput('horas_catedra');
        $horarios = $request->arrayInput('descripcion_horario');
        $rows = [];

        foreach ($ids as $index => $id) {
            $id = trim((string) $id);
            if ($id === '') {
                continue;
            }
            $rows[] = [
                'id' => $id,
                'disposicion' => $disposiciones[$index] ?? null,
                'horas_catedra' => $horas[$index] ?? 0,
                'descripcion_horario' => $horarios[$index] ?? null,
            ];
        }

        return $rows;
    }

    /**
     * @return list<array{
     *   id: string,
     *   fecha_toma: mixed,
     *   curso: mixed,
     *   dni_docente: mixed,
     *   estado: mixed,
     *   tipo_movimiento: mixed,
     *   estado_contralor: mixed
     * }>
     */
    private function tomaRows(Request $request): array
    {
        $ids = $request->arrayInput('toma_id');
        $fechas = $request->arrayInput('fecha_toma');
        $cursos = $request->arrayInput('curso');
        $dnis = $request->arrayInput('dni_docente');
        $estados = $request->arrayInput('estado');
        $tipos = $request->arrayInput('tipo_movimiento');
        $contralores = $request->arrayInput('estado_contralor');
        $rows = [];

        foreach ($ids as $index => $id) {
            $id = trim((string) $id);
            if ($id === '') {
                continue;
            }
            $rows[] = [
                'id' => $id,
                'fecha_toma' => $fechas[$index] ?? null,
                'curso' => $cursos[$index] ?? null,
                'dni_docente' => $dnis[$index] ?? null,
                'estado' => $estados[$index] ?? null,
                'tipo_movimiento' => $tipos[$index] ?? null,
                'estado_contralor' => $contralores[$index] ?? null,
            ];
        }

        return $rows;
    }

    private function nullableText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);

        return $value === '' ? null : $value;
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
