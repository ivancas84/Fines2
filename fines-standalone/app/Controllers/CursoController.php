<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\CursoRepository;

final class CursoController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $repository = new CursoRepository($this->pdo);
        $calendarios = $repository->calendarios();
        $selectedCalendario = (string) $request->query('calendario', '');
        if ($selectedCalendario === '' && $calendarios !== []) {
            $selectedCalendario = (string) $calendarios[0]['id'];
        }

        $this->view->render('cursos/index', [
            'title' => 'Cursos',
            'calendarios' => $calendarios,
            'selectedCalendario' => $selectedCalendario,
            'cursos' => $selectedCalendario === '' ? [] : $repository->porCalendario($selectedCalendario),
        ]);
    }
}
