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

function form_value(string $name, mixed $default = ''): string
{
    $value = query_form_value($name);

    return $value === null ? (string) ($default ?? '') : $value;
}

function form_checked(string $name, mixed $default = 0, mixed $expected = 1): string
{
    return checked(form_value($name, $default), $expected);
}

/** @param array<string, mixed> $values @param array<int, string> $fields */
function form_values(array $values, array $fields): array
{
    foreach ($fields as $field) {
        $value = query_form_value($field);
        if ($value !== null) {
            $values[$field] = $value;
        }
    }

    return $values;
}

function query_form_value(string $name): ?string
{
    $keys = [];
    if (preg_match('/^([^\[]+)((?:\[[^\]]*\])*)$/', $name, $matches) !== 1) {
        return null;
    }

    $keys[] = $matches[1];
    if ($matches[2] !== '') {
        preg_match_all('/\[([^\]]*)\]/', $matches[2], $nested);
        foreach ($nested[1] as $key) {
            if ($key === '') {
                return null;
            }
            $keys[] = $key;
        }
    }

    $value = $_GET;
    foreach ($keys as $key) {
        if (!is_array($value) || !array_key_exists($key, $value)) {
            return null;
        }
        $value = $value[$key];
    }

    return is_scalar($value) ? trim((string) $value) : null;
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
