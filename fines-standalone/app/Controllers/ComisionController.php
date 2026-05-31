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
}
