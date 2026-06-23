<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\InformeRepository;

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
}
