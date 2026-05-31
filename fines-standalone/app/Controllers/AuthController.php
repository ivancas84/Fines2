<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;

final class AuthController extends Controller
{
    public function showLogin(Request $request, array $vars = []): void
    {
        if ($this->auth->check()) {
            Response::redirect(url('/'));
        }

        $this->view->render('auth/login', [
            'title' => 'Ingresar',
            'error' => flash('error'),
        ]);
    }

    public function login(Request $request, array $vars = []): void
    {
        $this->csrf->validate($request->input('_token'));

        $email = strtolower((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if (!$this->auth->attempt($email, $password)) {
            Session::flash('error', 'Email o contrasena incorrectos.');
            Response::redirect(url('/login'));
        }

        Response::redirect(url('/'));
    }

    public function logout(Request $request, array $vars = []): void
    {
        $this->csrf->validate($request->input('_token'));
        $this->auth->logout();
        Response::redirect(url('/login'));
    }
}
