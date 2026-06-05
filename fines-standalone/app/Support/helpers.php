<?php

declare(strict_types=1);

use FinesApp\Core\Session;

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) ($_ENV['APP_BASE_PATH'] ?? ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function constancias_url(string $path = ''): string
{
    $base = rtrim((string) ($_ENV['CONSTANCIAS_PUBLIC_URL'] ?? ''), '/');
    if ($base === '') {
        return url($path);
    }

    return $base . '/' . ltrim($path, '/');
}

function selected(mixed $actual, mixed $expected): string
{
    return (string) $actual === (string) $expected ? 'selected' : '';
}

function checked(mixed $actual, mixed $expected = 1): string
{
    return (string) $actual === (string) $expected ? 'checked' : '';
}

function flash(string $key): ?string
{
    return Session::pullFlash($key);
}
