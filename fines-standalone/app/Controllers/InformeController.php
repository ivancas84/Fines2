<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\CalendarioRepository;
use FinesApp\Repositories\InformeRepository;
use FinesApp\Repositories\PlanillaDocenteRepository;
use FinesApp\Repositories\PlanRepository;

final class InformeController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $this->view->render('informes/index', [
            'title' => 'Informes',
        ]);
    }

    public function egresados(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $this->view->render('informes/egresados', [
            'title' => 'Egresados por calendario',
            'rows' => (new InformeRepository($this->pdo))->egresadosPorCalendario(),
        ]);
    }

    public function contralor(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $calendarioId = (string) $request->query('calendario', '');
        $planId = (string) $request->query('plan', '');
        $planillaId = (string) $request->query('planilla_docente', '');
        $submitted = $request->query('consultar') !== null
            || $calendarioId !== ''
            || $planillaId !== '';

        $error = flash('error') ?? null;
        $rows = [];
        $queryOk = false;
        if ($submitted) {
            $hasCalendario = $calendarioId !== '';
            $hasPlanilla = $planillaId !== '';
            if ($hasCalendario === $hasPlanilla) {
                $error = $hasCalendario
                    ? 'Elegí calendario o planilla docente, no ambos.'
                    : 'Elegí un calendario (sin planilla) o una planilla docente (sin calendario).';
            } else {
                $rows = (new InformeRepository($this->pdo))->tomasContralor([
                    'calendario_id' => $hasCalendario ? $calendarioId : null,
                    'planilla_id' => $hasPlanilla ? $planillaId : null,
                    'plan_id' => $planId !== '' ? $planId : null,
                ]);
                $queryOk = true;
            }
        }

        $this->view->render('informes/contralor', [
            'title' => 'Contralor',
            'calendarios' => (new CalendarioRepository($this->pdo))->all(),
            'planes' => (new PlanRepository($this->pdo))->all(),
            'planillas' => (new PlanillaDocenteRepository($this->pdo))->all(),
            'selectedCalendario' => $calendarioId,
            'selectedPlan' => $planId,
            'selectedPlanilla' => $planillaId,
            'submitted' => $submitted,
            'queryOk' => $queryOk,
            'rows' => $rows,
            'notice' => flash('notice'),
            'error' => $error,
        ]);
    }

    public function asignarPlanillaContralor(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $calendarioId = trim((string) $request->input('calendario', ''));
        $planId = trim((string) $request->input('plan', ''));
        $planillaId = trim((string) $request->input('asignar_planilla_id', ''));

        $returnQuery = array_filter([
            'calendario' => $calendarioId,
            'plan' => $planId,
            'consultar' => '1',
        ], static fn (string $value): bool => $value !== '');

        try {
            $updated = (new InformeRepository($this->pdo))->asignarPlanillaATomasSinPlanilla($planillaId, [
                'calendario_id' => $calendarioId,
                'plan_id' => $planId !== '' ? $planId : null,
            ]);
            $planilla = (new PlanillaDocenteRepository($this->pdo))->byId($planillaId);
            $label = (string) ($planilla['label'] ?? $planillaId);
            Session::flash('notice', "Se asignó la planilla «{$label}» a {$updated} toma(s) sin planilla.");
        } catch (\Throwable $throwable) {
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/informes/contralor?' . http_build_query($returnQuery)));
    }
}
