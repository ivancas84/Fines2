<?php

declare(strict_types=1);

use ConstanciasApp\Core\Session;

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) ($_ENV['APP_BASE_PATH'] ?? ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function checked(mixed $actual, mixed $expected = 1): string
{
    return (string) $actual === (string) $expected ? 'checked' : '';
}

function constancia_html(mixed $value): string
{
    $allowed = '<p><strong><u><i><br><h3><table><thead><tbody><tfoot><tr><th><td>';
    $html = strip_tags((string) ($value ?? ''), $allowed);
    return preg_replace('/<(\/?)(p|strong|u|i|br|h3|table|thead|tbody|tfoot|tr|th|td)\b[^>]*>/i', '<$1$2>', $html) ?? '';
}

function flash(string $key): ?string
{
    return Session::pullFlash($key);
}
