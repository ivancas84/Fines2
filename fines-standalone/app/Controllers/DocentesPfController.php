<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\CalendarioRepository;
use FinesApp\Repositories\DocentesPfImportRepository;

final class DocentesPfController extends Controller
{
    public function form(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $calendarios = (new CalendarioRepository($this->pdo))->all('anio', 'desc');
        $selectedCalendario = (string) $request->query('calendario', '');
        if ($selectedCalendario === '') {
            $selectedCalendario = trim($this->config->string('CALENDARIO_ID_ACTUAL', ''));
        }
        if ($selectedCalendario === '' && $calendarios !== []) {
            $selectedCalendario = (string) $calendarios[0]['id'];
        }

        $this->view->render('docentes/procesar_pf', [
            'title' => 'Procesar docentes PF',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'censTomas' => (string) $request->query('cens', '462'),
            'rawData' => '',
            'report' => null,
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function process(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $calendarios = (new CalendarioRepository($this->pdo))->all('anio', 'desc');
        $selectedCalendario = trim((string) $request->input('calendario', ''));
        $censTomas = trim((string) $request->input('cens_tomas', '462'));
        if ($censTomas === '') {
            $censTomas = '462';
        }
        $rawData = (string) $request->input('data', '');

        $report = null;
        $error = null;
        $notice = null;

        try {
            if (trim($rawData) === '') {
                throw new \InvalidArgumentException('Pegá los datos copiados desde el XLSX.');
            }
            if ($selectedCalendario === '') {
                throw new \InvalidArgumentException('Seleccioná un calendario.');
            }

            $report = (new DocentesPfImportRepository($this->pdo))->process(
                $rawData,
                $selectedCalendario,
                $censTomas,
            );

            $notice = sprintf(
                'Proceso finalizado: %d filas, %d insertados, %d existentes, %d modificados, %d tomas creadas, %d errores.',
                (int) $report['rows_total'],
                (int) $report['docentes_insertados'],
                (int) $report['docentes_existentes'],
                (int) $report['docentes_modificados'],
                (int) $report['tomas_creadas'],
                (int) $report['errores'],
            );
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        $this->view->render('docentes/procesar_pf', [
            'title' => 'Procesar docentes PF',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'censTomas' => $censTomas,
            'rawData' => $rawData,
            'report' => $report,
            'notice' => $notice,
            'error' => $error,
        ]);
    }
}
