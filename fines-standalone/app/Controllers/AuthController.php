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
            'googleEnabled' => $this->googleEnabled(),
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

    public function redirectToGoogle(Request $request, array $vars = []): void
    {
        if (!$this->googleEnabled()) {
            Session::flash('error', 'El login con Google no esta configurado.');
            Response::redirect(url('/login'));
        }

        $state = bin2hex(random_bytes(16));
        Session::put('google_oauth_state', $state);

        Response::redirect('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $this->config->string('GOOGLE_CLIENT_ID'),
            'redirect_uri' => $this->googleRedirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]));
    }

    public function handleGoogleCallback(Request $request, array $vars = []): void
    {
        $state = $request->query('state', '');
        $expectedState = Session::get('google_oauth_state');
        Session::forget('google_oauth_state');

        if ($state === '' || !is_string($expectedState) || !hash_equals($expectedState, $state)) {
            Session::flash('error', 'No se pudo validar el inicio de sesion con Google.');
            Response::redirect(url('/login'));
        }

        $code = $request->query('code', '');
        if ($code === '') {
            Session::flash('error', 'Google no devolvio un codigo de autorizacion.');
            Response::redirect(url('/login'));
        }

        try {
            $token = $this->googleToken($code);
            $owner = $this->googleUserInfo((string) ($token['access_token'] ?? ''));
            $email = strtolower((string) ($owner['email'] ?? ''));
            $emailVerified = $owner['email_verified'] ?? true;

            if ($email === '' || $emailVerified === false || $emailVerified === 'false') {
                Session::flash('error', 'La cuenta de Google no tiene un email verificado.');
                Response::redirect(url('/login'));
            }

            if (!$this->auth->attemptGoogle($email)) {
                Session::flash('error', 'Tu cuenta de Google no esta habilitada en Fines.');
                Response::redirect(url('/login'));
            }

            Response::redirect(url('/'));
        } catch (\Throwable) {
            Session::flash('error', 'No se pudo iniciar sesion con Google.');
            Response::redirect(url('/login'));
        }
    }

    public function logout(Request $request, array $vars = []): void
    {
        $this->csrf->validate($request->input('_token'));
        $this->auth->logout();
        Response::redirect(url('/login'));
    }

    private function googleEnabled(): bool
    {
        return $this->config->string('GOOGLE_CLIENT_ID') !== ''
            && $this->config->string('GOOGLE_CLIENT_SECRET') !== '';
    }

    private function googleRedirectUri(): string
    {
        return $this->config->string(
            'GOOGLE_REDIRECT_URI',
            $this->config->publicUrl() . '/login/google/callback',
        );
    }

    private function googleToken(string $code): array
    {
        return $this->httpJson('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $this->config->string('GOOGLE_CLIENT_ID'),
            'client_secret' => $this->config->string('GOOGLE_CLIENT_SECRET'),
            'redirect_uri' => $this->googleRedirectUri(),
            'grant_type' => 'authorization_code',
        ]);
    }

    private function googleUserInfo(string $accessToken): array
    {
        if ($accessToken === '') {
            throw new \RuntimeException('Access token vacio.');
        }

        return $this->httpJson('https://www.googleapis.com/oauth2/v3/userinfo', null, [
            'Authorization: Bearer ' . $accessToken,
        ]);
    }

    private function httpJson(string $url, ?array $postFields = null, array $headers = []): array
    {
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTPHEADER => $headers,
            ]);
            if ($postFields !== null) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
            }
            $body = curl_exec($curl);
            $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            if (!is_string($body) || $status < 200 || $status >= 300) {
                throw new \RuntimeException($error !== '' ? $error : 'HTTP ' . $status);
            }

            return $this->decodeJson($body);
        }

        $context = [
            'http' => [
                'method' => $postFields === null ? 'GET' : 'POST',
                'header' => implode("\r\n", array_merge(
                    $headers,
                    $postFields === null ? [] : ['Content-Type: application/x-www-form-urlencoded'],
                )),
                'content' => $postFields === null ? '' : http_build_query($postFields),
                'timeout' => 15,
            ],
        ];
        $body = file_get_contents($url, false, stream_context_create($context));
        if (!is_string($body)) {
            throw new \RuntimeException('No hubo respuesta HTTP.');
        }

        return $this->decodeJson($body);
    }

    private function decodeJson(string $body): array
    {
        $data = json_decode($body, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Respuesta JSON invalida.');
        }

        return $data;
    }
}
