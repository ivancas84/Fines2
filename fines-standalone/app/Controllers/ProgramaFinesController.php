<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Integrations\ProgramaFines\ProgramaFinesClient;
use FinesApp\Integrations\ProgramaFines\ProgramaFinesSession;

final class ProgramaFinesController extends Controller
{
    public function edit(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $connection = new ProgramaFinesSession();

        $this->view->render('programafines/edit', [
            'title' => 'ProgramaFines',
            'connected' => $connection->connected(),
            'maskedSession' => $this->masked($connection->id()),
            'periodo' => $this->periodo(),
            'notice' => flash('notice'),
            'error' => flash('error'),
        ]);
    }

    public function connect(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $this->csrf->validate($request->input('_token'));

        $sessionId = trim((string) $request->input('session_id', ''));
        if (preg_match('/^[A-Za-z0-9,-]{16,160}$/', $sessionId) !== 1) {
            Session::flash('error', 'El valor PHPSESS no tiene un formato válido.');
            Response::redirect(url('/programafines'));
        }

        try {
            (new ProgramaFinesClient($sessionId))->testConnection();
            (new ProgramaFinesSession())->connect($sessionId);
            Session::flash('notice', 'Conexión con ProgramaFines verificada.');
        } catch (\Throwable $throwable) {
            (new ProgramaFinesSession())->disconnect();
            Session::flash('error', $throwable->getMessage());
        }

        Response::redirect(url('/programafines'));
    }

    public function disconnect(Request $request, array $vars = []): void
    {
        $this->requireLogin();
        $this->csrf->validate($request->input('_token'));
        (new ProgramaFinesSession())->disconnect();
        Session::flash('notice', 'Se desconectó la sesión de ProgramaFines.');
        Response::redirect(url('/programafines'));
    }

    private function periodo(): int
    {
        return max(1, (int) $this->config->string('PROGRAMAFINES_PERIOD', '6'));
    }

    private function masked(?string $sessionId): string
    {
        if ($sessionId === null) {
            return '';
        }

        return substr($sessionId, 0, 4) . str_repeat('•', max(8, strlen($sessionId) - 8)) . substr($sessionId, -4);
    }
}
