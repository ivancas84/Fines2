<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\PersonaRepository;

final class PersonaController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        $this->requireLogin();

        $search = (string) $request->query('q', '');
        $personas = [];
        $submitted = $search !== '';

        if ($submitted) {
            $personas = (new PersonaRepository($this->pdo))->search($search);
        }

        $data = [
            'title' => 'Personas',
            'search' => $search,
            'submitted' => $submitted,
            'personas' => $personas,
        ];

        if ($request->isHtmx()) {
            $this->view->renderPartial('personas/_results', $data);
            return;
        }

        $this->view->render('personas/index', $data);
    }
}
