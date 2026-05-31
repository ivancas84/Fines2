<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;

final class DashboardController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $this->view->render('dashboard', [
            'title' => 'Inicio',
        ]);
    }
}
