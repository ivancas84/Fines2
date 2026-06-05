<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Request;
use ConstanciasApp\Core\Response;
use ConstanciasApp\Core\Session;

final class AuthController extends Controller
{
    public function showLogin(Request $request, array $vars = []): void
    {
        if ($this->auth->check()) {
            Response::redirect(url('/'));
        }

        $this->view->render('auth/login', ['title' => 'Ingresar', 'error' => flash('error')]);
    }

    public function login(Request $request, array $vars = []): void
    {
        $this->csrf->validate($request->input('_token'));
        if (!$this->auth->attempt(strtolower((string) $request->input('email', '')), (string) $request->input('password', ''))) {
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
