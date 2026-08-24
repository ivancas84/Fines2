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
        throw new RuntimeException('Falta configurar CONSTANCIAS_PUBLIC_URL.');
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

function persona_cuil(array $persona): string
{
    $cuil1 = trim((string) ($persona['cuil1'] ?? ''));
    $dni = preg_replace('/\D+/', '', (string) ($persona['numero_documento'] ?? '')) ?? '';
    $cuil2 = trim((string) ($persona['cuil2'] ?? ''));

    if ($cuil1 === '' || $dni === '' || $cuil2 === '') {
        return $dni;
    }

    return str_pad($cuil1, 2, '0', STR_PAD_LEFT)
        . '-'
        . str_pad($dni, 8, '0', STR_PAD_LEFT)
        . '-'
        . substr($cuil2, -1);
}

function persona_whatsapp_url(array $persona): ?string
{
    $telefono = preg_replace('/\D+/', '', (string) ($persona['telefono'] ?? '')) ?? '';
    if ($telefono === '') {
        return null;
    }

    $partes = preg_split('/\s+/u', trim((string) ($persona['nombres'] ?? ''))) ?: [];
    $primerNombre = trim((string) ($partes[0] ?? ''));
    $texto = $primerNombre !== '' ? 'Hola ' . $primerNombre : 'Hola';

    return 'https://web.whatsapp.com/send/?phone=' . rawurlencode($telefono)
        . '&text=' . rawurlencode($texto);
}

function persona_fecha_nacimiento(array $persona): string
{
    $dia = (int) ($persona['dia_nacimiento'] ?? 0);
    $mes = (int) ($persona['mes_nacimiento'] ?? 0);
    $anio = (int) ($persona['anio_nacimiento'] ?? 0);

    if ($dia < 1 || $mes < 1 || $anio < 1 || !checkdate($mes, $dia, $anio)) {
        return '';
    }

    return sprintf('%02d/%02d/%04d', $dia, $mes, $anio);
}

function flash(string $key): ?string
{
    return Session::pullFlash($key);
}

function import_log_level_class(string $level): string
{
    return match ($level) {
        'success' => 'text-success',
        'warning' => 'text-warning-emphasis',
        'conflict' => 'import-log-conflict-text',
        'error' => 'text-danger',
        default => 'text-secondary',
    };
}

function import_log_row_class(string $level): string
{
    return $level === 'conflict' ? 'import-log-conflict' : '';
}
