<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
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
        ]);
    }
}
