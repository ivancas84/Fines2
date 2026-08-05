<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\SedeRepository;

final class SedeController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $sort = (string) $request->query('sort', 'numero');
        $order = (string) $request->query('order', 'asc');

        if (!in_array($sort, ['numero', 'nombre', 'cens', 'fecha_traspaso'], true)) {
            $sort = 'numero';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }

        $sedes = (new SedeRepository($this->pdo))->all($sort, $order);

        $this->view->render('sedes/index', [
            'title' => 'Sedes',
            'sedes' => $sedes,
            'sort' => $sort,
            'order' => $order,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function createForm(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $this->renderForm(null, true, 'Nueva sede');
    }

    public function create(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        try {
            $id = (new SedeRepository($this->pdo))->create($this->sedeFormData($request));
            Session::flash('notice', 'Sede creada.');
            Response::redirect(url('/sedes/' . rawurlencode($id)));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url('/sedes/nueva'));
        }
    }

    public function edit(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $id = trim((string) ($vars['id'] ?? ''));
        $sede = $id !== '' ? (new SedeRepository($this->pdo))->byId($id) : null;
        if ($sede === null) {
            $this->view->render('errors/404', ['title' => 'Sede no encontrada'], 404);
            return;
        }

        $this->renderForm($sede, false, 'Editar sede');
    }

    public function update(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $id = trim((string) ($vars['id'] ?? ''));

        try {
            $repository = new SedeRepository($this->pdo);
            if ($repository->byId($id) === null) {
                throw new \RuntimeException('La sede no existe.');
            }
            $repository->update($id, $this->sedeFormData($request));
            Session::flash('notice', 'Sede guardada.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/sedes/' . rawurlencode($id)));
    }

    public function addDesignacion(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $sedeId = trim((string) ($vars['id'] ?? ''));

        try {
            $cargo = trim((string) $request->input('cargo', ''));
            $dni = preg_replace('/\D+/', '', (string) $request->input('dni', '')) ?? '';
            if ($cargo === '') {
                throw new \InvalidArgumentException('Seleccioná un cargo.');
            }
            if ($dni === '') {
                throw new \InvalidArgumentException('Ingresá el DNI de la persona.');
            }

            $desde = $this->nullableDate($request->input('desde'), 'desde');
            $hasta = $this->nullableDate($request->input('hasta'), 'hasta');
            if ($desde !== null && $hasta !== null && $hasta < $desde) {
                throw new \InvalidArgumentException('La fecha hasta no puede ser anterior a desde.');
            }

            (new SedeRepository($this->pdo))->addDesignacion($sedeId, [
                'cargo' => $cargo,
                'dni' => $dni,
                'desde' => $desde,
                'hasta' => $hasta,
            ]);
            Session::flash('notice', 'Designación agregada.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/sedes/' . rawurlencode($sedeId)));
    }

    public function saveDesignaciones(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $sedeId = trim((string) ($vars['id'] ?? ''));

        try {
            $repository = new SedeRepository($this->pdo);
            $deleteId = trim((string) $request->input('delete_designacion_id', ''));
            if ($deleteId !== '') {
                $repository->deleteDesignacion($sedeId, $deleteId);
                Session::flash('notice', 'Designación eliminada.');
            } else {
                $repository->updateDesignaciones($sedeId, $this->designacionRows($request));
                Session::flash('notice', 'Designaciones guardadas.');
            }
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/sedes/' . rawurlencode($sedeId)));
    }

    /**
     * @param array<string, mixed>|null $sede
     */
    private function renderForm(?array $sede, bool $isNew, string $title): void
    {
        $repository = new SedeRepository($this->pdo);
        $sedeId = (string) ($sede['id'] ?? '');

        if ($sede === null) {
            $sede = [
                'id' => '',
                'numero' => '',
                'nombre' => '',
                'observaciones' => '',
                'fecha_traspaso' => '',
                'tipo_sede_id' => '',
                'centro_educativo_id' => '',
                'pfid' => '',
                'domicilio_id' => '',
                'domicilio_calle' => '',
                'domicilio_numero' => '',
                'domicilio_entre' => '',
                'domicilio_piso' => '',
                'domicilio_departamento' => '',
                'domicilio_barrio' => '',
                'domicilio_localidad' => '',
            ];
        }

        $this->view->render('sedes/form', [
            'title' => $title,
            'isNew' => $isNew,
            'sede' => $sede,
            'tiposSede' => $repository->tiposSedeOptions(),
            'centrosEducativos' => $repository->centrosEducativosOptions(),
            'cargos' => $repository->cargosOptions(),
            'designaciones' => $isNew || $sedeId === '' ? [] : $repository->designacionesBySede($sedeId),
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    /**
     * @return array{
     *   numero: string,
     *   nombre: string,
     *   observaciones: ?string,
     *   fecha_traspaso: ?string,
     *   tipo_sede: ?string,
     *   centro_educativo: ?string,
     *   pfid: ?string,
     *   domicilio: ?array{
     *     calle: string,
     *     numero: string,
     *     entre: ?string,
     *     piso: ?string,
     *     departamento: ?string,
     *     barrio: ?string,
     *     localidad: string
     *   }
     * }
     */
    private function sedeFormData(Request $request): array
    {
        $numero = trim((string) $request->input('numero', ''));
        $nombre = trim((string) $request->input('nombre', ''));
        if ($numero === '') {
            throw new \InvalidArgumentException('El número de sede es obligatorio.');
        }
        if ($nombre === '') {
            throw new \InvalidArgumentException('El nombre de sede es obligatorio.');
        }
        if (mb_strlen($numero) > 45) {
            throw new \InvalidArgumentException('El número no puede superar los 45 caracteres.');
        }
        if (mb_strlen($nombre) > 255) {
            throw new \InvalidArgumentException('El nombre no puede superar los 255 caracteres.');
        }

        $observaciones = trim((string) $request->input('observaciones', ''));
        $pfid = trim((string) $request->input('pfid', ''));
        $tipoSede = trim((string) $request->input('tipo_sede', ''));
        $centroEducativo = trim((string) $request->input('centro_educativo', ''));
        $fechaTraspaso = $this->nullableDate($request->input('fecha_traspaso'), 'fecha de traspaso');

        return [
            'numero' => $numero,
            'nombre' => $nombre,
            'observaciones' => $observaciones === '' ? null : $observaciones,
            'fecha_traspaso' => $fechaTraspaso,
            'tipo_sede' => $tipoSede === '' ? null : $tipoSede,
            'centro_educativo' => $centroEducativo === '' ? null : $centroEducativo,
            'pfid' => $pfid === '' ? null : $pfid,
            'domicilio' => $this->domicilioFormData($request),
        ];
    }

    /**
     * @return ?array{
     *   calle: string,
     *   numero: string,
     *   entre: ?string,
     *   piso: ?string,
     *   departamento: ?string,
     *   barrio: ?string,
     *   localidad: string
     * }
     */
    private function domicilioFormData(Request $request): ?array
    {
        $calle = trim((string) $request->input('domicilio_calle', ''));
        $numero = trim((string) $request->input('domicilio_numero', ''));
        $entre = trim((string) $request->input('domicilio_entre', ''));
        $piso = trim((string) $request->input('domicilio_piso', ''));
        $departamento = trim((string) $request->input('domicilio_departamento', ''));
        $barrio = trim((string) $request->input('domicilio_barrio', ''));
        $localidad = trim((string) $request->input('domicilio_localidad', ''));

        $anyFilled = $calle !== '' || $numero !== '' || $entre !== '' || $piso !== ''
            || $departamento !== '' || $barrio !== '' || $localidad !== '';

        if (!$anyFilled) {
            return null;
        }

        if ($calle === '' || $numero === '' || $localidad === '') {
            throw new \InvalidArgumentException(
                'Si cargás domicilio, calle, número y localidad son obligatorios.',
            );
        }

        return [
            'calle' => $calle,
            'numero' => $numero,
            'entre' => $entre === '' ? null : $entre,
            'piso' => $piso === '' ? null : $piso,
            'departamento' => $departamento === '' ? null : $departamento,
            'barrio' => $barrio === '' ? null : $barrio,
            'localidad' => $localidad,
        ];
    }

    /**
     * @return list<array{id: string, cargo: string, desde: ?string, hasta: ?string}>
     */
    private function designacionRows(Request $request): array
    {
        $ids = $request->arrayInput('designacion_id');
        $cargos = $request->arrayInput('cargo');
        $desdes = $request->arrayInput('desde');
        $hastas = $request->arrayInput('hasta');

        $rows = [];
        foreach ($ids as $index => $rawId) {
            $id = trim((string) $rawId);
            if ($id === '') {
                continue;
            }

            $cargo = trim((string) ($cargos[$index] ?? ''));
            if ($cargo === '') {
                throw new \InvalidArgumentException('Todas las designaciones deben tener un cargo.');
            }

            $desde = $this->nullableDate(
                isset($desdes[$index]) ? (string) $desdes[$index] : null,
                'desde',
            );
            $hasta = $this->nullableDate(
                isset($hastas[$index]) ? (string) $hastas[$index] : null,
                'hasta',
            );
            if ($desde !== null && $hasta !== null && $hasta < $desde) {
                throw new \InvalidArgumentException('En una designación, hasta no puede ser anterior a desde.');
            }

            $rows[] = [
                'id' => $id,
                'cargo' => $cargo,
                'desde' => $desde,
                'hasta' => $hasta,
            ];
        }

        return $rows;
    }

    private function nullableDate(?string $value, string $label): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $raw = trim($value);
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $raw);
        $errors = \DateTimeImmutable::getLastErrors();
        $hasErrors = is_array($errors)
            && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0);

        if (!$date instanceof \DateTimeImmutable || $hasErrors || $date->format('Y-m-d') !== $raw) {
            throw new \InvalidArgumentException("La fecha de {$label} no es válida.");
        }

        return $raw;
    }
}
