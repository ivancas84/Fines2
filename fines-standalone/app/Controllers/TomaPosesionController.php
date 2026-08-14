<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Repositories\CalendarioRepository;
use FinesApp\Repositories\CursoRepository;

/**
 * Listado público de cursos para tomar posesión
 * (migrado de wp/tp2_toma_posesion).
 */
final class TomaPosesionController extends Controller
{
    public function index(Request $request, array $vars = []): void
    {
        // Público: no requiere login.

        $calendarioRepo = new CalendarioRepository($this->pdo);
        $configuredId = trim($this->config->string('CALENDARIO_ID_ACTUAL', ''));
        $calendario = $calendarioRepo->resolveActual($configuredId !== '' ? $configuredId : null);

        $cursos = [];
        $calendarioFromConfig = false;
        if ($calendario !== null && $calendario['id'] !== '') {
            $calendarioFromConfig = $configuredId !== '' && $configuredId === $calendario['id'];
            $cursos = (new CursoRepository($this->pdo))->autorizadosPublicadosByCalendario($calendario['id']);
        }

        $formBase = rtrim($this->config->string(
            'TOMA_POSESION_FORM_URL',
            'https://planfines2.com.ar/wp/toma-de-posesion/',
        ), '?&');

        $this->view->render('toma_posesion/index', [
            'layout' => 'layouts/public',
            'title' => 'Toma de posesión',
            'calendario' => $calendario,
            'calendarioFromConfig' => $calendarioFromConfig,
            'cursos' => $cursos,
            'formBaseUrl' => $formBase,
        ]);
    }
}
