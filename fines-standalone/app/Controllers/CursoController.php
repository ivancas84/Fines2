<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\CursoRepository;
use FinesApp\Repositories\PlanillaCalificacionRepository;
use FinesApp\Repositories\TomaRepository;
use FinesApp\Support\ExcelParser;
use FinesApp\Support\PlanillaCalificacionParser;

final class CursoController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $repository = new CursoRepository($this->pdo);
        $calendarios = $repository->calendarios();
        $selectedCalendario = (string) $request->query('calendario', '');
        if ($selectedCalendario === '' && $calendarios !== []) {
            $selectedCalendario = (string) $calendarios[0]['id'];
        }

        $this->view->render('cursos/index', [
            'title' => 'Cursos',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'cursos' => $selectedCalendario === '' ? [] : $repository->porCalendario($selectedCalendario),
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function marcarPlanillaEntregada(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $curso = $this->requireCurso((string) ($vars['id'] ?? ''));
        $cursoId = (string) $curso['curso_id'];
        $tomaId = trim((string) ($curso['toma_id'] ?? ''));
        $returnTo = (string) $request->input('return', 'planilla');
        $calendarioQuery = $this->calendarioQuery($curso);

        try {
            if ($tomaId === '') {
                throw new \RuntimeException('El curso no tiene toma activa para marcar la planilla.');
            }

            (new TomaRepository($this->pdo))->updateEstadoPlanilla($tomaId, 'entregada');
            Session::flash('notice', 'Estado de planilla actualizado a entregada.');
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        if ($returnTo === 'cursos') {
            Response::redirect(url('/cursos' . $calendarioQuery));
        }

        Response::redirect(url('/cursos/' . rawurlencode($cursoId) . '/planilla'));
    }

    public function planilla(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $curso = $this->requireCurso((string) ($vars['id'] ?? ''));
        $calendarioQuery = $this->calendarioQuery($curso);

        $this->view->render('cursos/planilla', [
            'title' => 'Cargar planilla de calificación',
            'curso' => $curso,
            'formats' => PlanillaCalificacionParser::FORMATS,
            'selectedFormat' => 'PF2',
            'rawData' => '',
            'observaciones' => '',
            'preview' => null,
            'calendarioQuery' => $calendarioQuery,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function planillaConsultar(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $this->csrf->validate($request->input('_token'));

        $curso = $this->requireCurso((string) ($vars['id'] ?? ''));
        $calendarioQuery = $this->calendarioQuery($curso);
        $format = strtoupper((string) $request->input('format', 'PF2'));
        $rawData = (string) $request->input('data', '');
        $observaciones = (string) $request->input('observaciones', '');

        if (!in_array($format, PlanillaCalificacionParser::FORMATS, true)) {
            Session::flash('error', 'Formato no reconocido.');
            Response::redirect(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla'));
        }

        if (trim($rawData) === '') {
            Session::flash('error', 'Pegá los datos de la planilla antes de consultar.');
            Response::redirect(url('/cursos/' . rawurlencode((string) $curso['curso_id']) . '/planilla'));
        }

        $rows = ExcelParser::parseIgnorePrefix($rawData);
        $preview = $this->processRows($curso, $rows, $format, $observaciones, false);

        $this->view->render('cursos/planilla', [
            'title' => 'Cargar planilla de calificación',
            'curso' => $curso,
            'formats' => PlanillaCalificacionParser::FORMATS,
            'selectedFormat' => $format,
            'rawData' => $rawData,
            'observaciones' => $observaciones,
            'preview' => $preview,
            'calendarioQuery' => $calendarioQuery,
            'notice' => null,
            'error' => null,
        ]);
    }

    public function planillaGuardar(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $curso = $this->requireCurso((string) ($vars['id'] ?? ''));
        $format = strtoupper((string) $request->input('format', 'PF2'));
        $rawData = (string) $request->input('data', '');
        $observaciones = (string) $request->input('observaciones', '');
        $cursoId = (string) $curso['curso_id'];

        if (!in_array($format, PlanillaCalificacionParser::FORMATS, true)) {
            Session::flash('error', 'Formato no reconocido.');
            Response::redirect(url('/cursos/' . rawurlencode($cursoId) . '/planilla'));
        }

        if (trim($rawData) === '') {
            Session::flash('error', 'No hay datos para guardar.');
            Response::redirect(url('/cursos/' . rawurlencode($cursoId) . '/planilla'));
        }

        $rows = ExcelParser::parseIgnorePrefix($rawData);
        $result = $this->processRows($curso, $rows, $format, $observaciones, true);

        $saved = 0;
        $errors = 0;
        foreach ($result['items'] as $item) {
            if (!empty($item['ok']) && !empty($item['has_changes'])) {
                $saved++;
            }
            if (empty($item['ok'])) {
                $errors++;
            }
        }

        if ($saved === 0 && $errors === 0) {
            Session::flash('notice', 'No existen datos nuevos para registrar.');
        } elseif ($errors === 0) {
            Session::flash('notice', "Se guardaron {$saved} calificaciones.");
        } else {
            Session::flash(
                'error',
                "Se guardaron {$saved} calificaciones. Filas con error: {$errors}.",
            );
        }

        Response::redirect(url('/cursos/' . rawurlencode($cursoId) . '/planilla'));
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return array{total: int, with_changes: int, errors: int, items: list<array<string, mixed>>}
     */
    private function processRows(
        array $curso,
        array $rows,
        string $format,
        string $observaciones,
        bool $persist,
    ): array {
        $repository = new PlanillaCalificacionRepository($this->pdo);
        $items = [];
        $withChanges = 0;
        $errors = 0;

        foreach ($rows as $index => $row) {
            try {
                $parsed = PlanillaCalificacionParser::parse($row, $format);
                $item = $repository->processRow($curso, $parsed, $observaciones, $persist);
                $item['row_number'] = $index + 1;
                $item['parsed'] = $parsed;
                $items[] = $item;
                if (!empty($item['has_changes'])) {
                    $withChanges++;
                }
                if (empty($item['ok'])) {
                    $errors++;
                }
            } catch (\Throwable $throwable) {
                $errors++;
                $items[] = [
                    'row_number' => $index + 1,
                    'ok' => false,
                    'label' => 'Fila ' . ($index + 1),
                    'actions' => [],
                    'has_changes' => false,
                    'error' => $throwable->getMessage(),
                    'parsed' => null,
                    'raw' => $row,
                ];
            }
        }

        return [
            'total' => count($rows),
            'with_changes' => $withChanges,
            'errors' => $errors,
            'items' => $items,
        ];
    }

    /** @return array<string, mixed> */
    private function requireCurso(string $cursoId): array
    {
        $cursoId = trim($cursoId);
        if ($cursoId === '') {
            $this->view->render('errors/404', ['title' => 'Curso no encontrado'], 404);
            exit;
        }

        $curso = (new CursoRepository($this->pdo))->byId($cursoId);
        if ($curso === null) {
            $this->view->render('errors/404', ['title' => 'Curso no encontrado'], 404);
            exit;
        }

        return $curso;
    }

    private function calendarioQuery(array $curso): string
    {
        $calendarioId = trim((string) ($curso['calendario_id'] ?? ''));

        return $calendarioId !== '' ? '?calendario=' . rawurlencode($calendarioId) : '';
    }
}
