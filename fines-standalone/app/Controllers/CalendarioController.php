<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\CalendarioRepository;

final class CalendarioController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $sort = (string) $request->query('sort', 'anio');
        $order = (string) $request->query('order', 'desc');

        if (!in_array($sort, ['anio', 'semestre', 'inicio', 'fin', 'descripcion', 'comisiones'], true)) {
            $sort = 'anio';
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'desc';
        }

        $calendarios = (new CalendarioRepository($this->pdo))->all($sort, $order);

        $this->view->render('calendarios/index', [
            'title' => 'Calendarios',
            'calendarios' => $calendarios,
            'sort' => $sort,
            'order' => $order,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function createForm(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');
        $defaultSemestre = $currentMonth <= 6 ? 1 : 2;

        $this->view->render('calendarios/form', [
            'title' => 'Nuevo calendario',
            'isNew' => true,
            'calendario' => [
                'id' => '',
                'anio' => $currentYear,
                'semestre' => $defaultSemestre,
                'inicio' => '',
                'fin' => '',
                'descripcion' => '',
                'comisiones_count' => 0,
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
            $data = $this->formData($request);
            $id = (new CalendarioRepository($this->pdo))->create($data);
            Session::flash('notice', 'Calendario creado.');
            Response::redirect(url('/calendarios/' . rawurlencode($id)));
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
            Response::redirect(url('/calendarios/nuevo'));
        }
    }

    public function edit(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $id = trim((string) ($vars['id'] ?? ''));
        $calendario = $id !== '' ? (new CalendarioRepository($this->pdo))->byId($id) : null;

        if ($calendario === null) {
            $this->view->render('errors/404', ['title' => 'Calendario no encontrado'], 404);
            return;
        }

        $this->view->render('calendarios/form', [
            'title' => 'Editar calendario',
            'isNew' => false,
            'calendario' => $calendario,
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
            $repository = new CalendarioRepository($this->pdo);
            if ($repository->byId($id) === null) {
                throw new \RuntimeException('El calendario no existe.');
            }

            $repository->update($id, $this->formData($request));
            Session::flash('notice', 'Calendario guardado.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/calendarios/' . rawurlencode($id)));
    }

    /**
     * @return array{
     *   anio: int,
     *   semestre: int,
     *   inicio: ?string,
     *   fin: ?string,
     *   descripcion: ?string
     * }
     */
    private function formData(Request $request): array
    {
        $anioRaw = trim((string) $request->input('anio', ''));
        if ($anioRaw === '' || filter_var($anioRaw, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException('El año es obligatorio y debe ser un número entero.');
        }
        $anio = (int) $anioRaw;
        if ($anio < 2000 || $anio > 2100) {
            throw new \InvalidArgumentException('El año debe estar entre 2000 y 2100.');
        }

        $semestreRaw = trim((string) $request->input('semestre', ''));
        if ($semestreRaw === '' || filter_var($semestreRaw, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException('El semestre es obligatorio.');
        }
        $semestre = (int) $semestreRaw;
        if ($semestre < 1 || $semestre > 2) {
            throw new \InvalidArgumentException('El semestre debe ser 1 o 2.');
        }

        $inicio = $this->nullableDate($request->input('inicio'), 'inicio');
        $fin = $this->nullableDate($request->input('fin'), 'fin');

        if ($inicio !== null && $fin !== null && $fin < $inicio) {
            throw new \InvalidArgumentException('La fecha de fin no puede ser anterior a la de inicio.');
        }

        $descripcion = trim((string) $request->input('descripcion', ''));
        if (mb_strlen($descripcion) > 255) {
            throw new \InvalidArgumentException('La descripción no puede superar los 255 caracteres.');
        }

        return [
            'anio' => $anio,
            'semestre' => $semestre,
            'inicio' => $inicio,
            'fin' => $fin,
            'descripcion' => $descripcion === '' ? null : $descripcion,
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
