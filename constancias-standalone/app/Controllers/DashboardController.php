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
        $search = (string) $request->query('q', '');
        $page = max(1, (int) $request->query('page', '1'));
        $result = (new ConstanciaRepository($this->pdo))->search($establecimiento['id'] ?? null, $search, $page, 20);

        $this->view->render('dashboard', [
            'title' => 'Constancias',
            'establecimiento' => $establecimiento,
            'constancias' => $result['rows'],
            'search' => $search,
            'pagination' => $result,
            'notice' => flash('notice'),
        ]);
    }
}
