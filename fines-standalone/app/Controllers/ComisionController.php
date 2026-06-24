<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
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

        $this->view->render('comisiones/alumnos', [
            'title' => 'Alumnos de la comision',
            'comision' => $comision,
            'alumnos' => $repository->alumnos($comisionId),
        ]);
    }
}
