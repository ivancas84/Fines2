<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\CalendarioRepository;
use FinesApp\Repositories\ComisionesPciImportRepository;

final class ComisionesPciController extends Controller
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

        $this->view->render('comisiones/procesar_pci', [
            'title' => 'Procesar comisiones PCI',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
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
        $rawData = (string) $request->input('data', '');

        $report = null;
        $error = null;
        $notice = null;

        try {
            if (trim($rawData) === '') {
                throw new \InvalidArgumentException('Pegá los datos copiados del informe global PCI.');
            }
            if ($selectedCalendario === '') {
                throw new \InvalidArgumentException('Seleccioná un calendario.');
            }

            $report = (new ComisionesPciImportRepository($this->pdo))->process(
                $rawData,
                $selectedCalendario,
            );

            $notice = sprintf(
                'Proceso finalizado: %d comisiones, %d áreas, %d cursos, %d horarios, %d tomas creadas, %d conflictos, %d errores.',
                (int) $report['comisiones_detectadas'],
                (int) $report['areas_detectadas'],
                (int) $report['cursos_procesados'],
                (int) $report['horarios_actualizados'],
                (int) $report['tomas_creadas'],
                (int) $report['tomas_conflicto'],
                (int) $report['errores'],
            );
        } catch (\Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        $this->view->render('comisiones/procesar_pci', [
            'title' => 'Procesar comisiones PCI',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'rawData' => $rawData,
            'report' => $report,
            'notice' => $notice,
            'error' => $error,
        ]);
    }
}
