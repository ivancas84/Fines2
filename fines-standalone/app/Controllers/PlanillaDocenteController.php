<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\PlanillaDocenteRepository;

final class PlanillaDocenteController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $sort = (string) $request->query('sort', 'insertado');
        $order = (string) $request->query('order', 'desc');

        if (!in_array($sort, ['numero', 'fecha_contralor', 'fecha_consejo', 'tomas', 'insertado'], true)) {
            $sort = 'insertado';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'desc';
        }

        $this->view->render('planillas_docente/index', [
            'title' => 'Planillas docente',
            'planillas' => (new PlanillaDocenteRepository($this->pdo))->all($sort, $order),
            'sort' => $sort,
            'order' => $order,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function createForm(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $this->view->render('planillas_docente/form', [
            'title' => 'Nueva planilla docente',
            'isNew' => true,
            'planilla' => [
                'id' => '',
                'numero' => '',
                'fecha_contralor' => '',
                'fecha_consejo' => '',
                'observaciones' => '',
                'tomas_count' => 0,
            ],
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function create(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        try {
            $id = (new PlanillaDocenteRepository($this->pdo))->create($this->formData($request));
            Session::flash('notice', 'Planilla docente creada.');
            Response::redirect(url('/planillas-docente/' . rawurlencode($id)));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url('/planillas-docente/nueva'));
        }
    }

    public function edit(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $id = trim((string) ($vars['id'] ?? ''));
        $planilla = $id !== '' ? (new PlanillaDocenteRepository($this->pdo))->byId($id) : null;
        if ($planilla === null) {
            $this->view->render('errors/404', ['title' => 'Planilla docente no encontrada'], 404);
            return;
        }

        $this->view->render('planillas_docente/form', [
            'title' => 'Editar planilla docente',
            'isNew' => false,
            'planilla' => $planilla,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function update(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $id = trim((string) ($vars['id'] ?? ''));

        try {
            $repository = new PlanillaDocenteRepository($this->pdo);
            if ($repository->byId($id) === null) {
                throw new \RuntimeException('La planilla docente no existe.');
            }

            $repository->update($id, $this->formData($request));
            Session::flash('notice', 'Planilla docente guardada.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/planillas-docente/' . rawurlencode($id)));
    }

    /**
     * @return array{
     *   numero: string,
     *   fecha_contralor: ?string,
     *   fecha_consejo: ?string,
     *   observaciones: ?string
     * }
     */
    private function formData(Request $request): array
    {
        $numero = trim((string) $request->input('numero', ''));
        if ($numero === '') {
            throw new \InvalidArgumentException('El número o nombre de la planilla es obligatorio.');
        }
        if (mb_strlen($numero) > 255) {
            throw new \InvalidArgumentException('El número no puede superar los 255 caracteres.');
        }

        $observaciones = trim((string) $request->input('observaciones', ''));

        return [
            'numero' => $numero,
            'fecha_contralor' => $this->nullableDate($request->input('fecha_contralor'), 'contralor'),
            'fecha_consejo' => $this->nullableDate($request->input('fecha_consejo'), 'consejo'),
            'observaciones' => $observaciones === '' ? null : $observaciones,
        ];
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
