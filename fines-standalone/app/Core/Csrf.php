<?php

declare(strict_types=1);

namespace FinesApp\Core;

final class Csrf
{
    public function token(): string
    {
        $token = Session::get('_csrf_token');
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::put('_csrf_token', $token);
        }

        return $token;
    }

    public function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e($this->token()) . '">';
    }

    public function validate(?string $token): void
    {
        if (!is_string($token) || !hash_equals($this->token(), $token)) {
            throw new \RuntimeException('Token CSRF invalido. Recarga la pagina e intenta nuevamente.');
        }
    }
}
