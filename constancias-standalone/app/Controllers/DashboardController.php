<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Request;
use ConstanciasApp\Repositories\ConstanciaRepository;
use ConstanciasApp\Repositories\EstablecimientoRepository;

final class DashboardController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $user = $this->auth->user();
        $establecimiento = $user === null ? null : (new EstablecimientoRepository($this->pdo))->byUser((int) $user['id']);

        $this->view->render('dashboard', [
            'title' => 'Constancias',
            'establecimiento' => $establecimiento,
            'constancias' => (new ConstanciaRepository($this->pdo))->latest($establecimiento['id'] ?? null),
            'notice' => flash('notice'),
        ]);
    }
}
