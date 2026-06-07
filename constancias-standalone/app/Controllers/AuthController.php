<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Request;
use ConstanciasApp\Core\Response;
use ConstanciasApp\Core\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;

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
        Session::flash('error', 'El ingreso con usuario y contrasena esta deshabilitado. Usa Google.');
        Response::redirect(url('/login'));
    }

    public function redirectToGoogle(Request $request, array $vars = []): void
    {
        if (!$this->googleEnabled()) {
            Session::flash('error', 'El login con Google no esta configurado.');
            Response::redirect(url('/login'));
        }

        $provider = $this->googleProvider();
        $authorizationUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile'],
        ]);

        Session::put('google_oauth_state', $provider->getState());
        Response::redirect($authorizationUrl);
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
            $provider = $this->googleProvider();
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $owner = $provider->getResourceOwner($token);
            $ownerData = $owner->toArray();
            $email = strtolower((string) $owner->getEmail());
            $emailVerified = $ownerData['email_verified'] ?? $ownerData['verified_email'] ?? true;

            if ($email === '' || $emailVerified === false || $emailVerified === 'false') {
                Session::flash('error', 'La cuenta de Google no tiene un email verificado.');
                Response::redirect(url('/login'));
            }

            if (!$this->auth->attemptGoogle($email)) {
                Session::flash('error', 'Tu cuenta de Google no esta habilitada en Constancias.');
                Response::redirect(url('/login'));
            }

            Response::redirect(url('/'));
        } catch (IdentityProviderException) {
            Session::flash('error', 'Google rechazo el inicio de sesion.');
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

    private function googleProvider(): Google
    {
        return new Google([
            'clientId' => $this->config->string('GOOGLE_CLIENT_ID'),
            'clientSecret' => $this->config->string('GOOGLE_CLIENT_SECRET'),
            'redirectUri' => $this->config->string(
                'GOOGLE_REDIRECT_URI',
                $this->config->publicUrl() . '/login/google/callback',
            ),
        ]);
    }
}
