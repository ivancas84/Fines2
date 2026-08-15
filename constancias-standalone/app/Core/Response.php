<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

final class Response
{
    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }

    public static function text(string $text, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/plain; charset=utf-8');
        echo $text;
        exit;
    }

    public static function file(string $path, string $downloadName, string $mimeType = 'application/octet-stream'): void
    {
        if (!is_file($path) || !is_readable($path)) {
            self::text('Archivo no encontrado', 404);
        }

        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . str_replace('"', '', $downloadName) . '"');
        readfile($path);
        exit;
    }

    public static function inlineFile(string $path, string $fileName, string $mimeType = 'application/octet-stream'): void
    {
        if (!is_file($path) || !is_readable($path)) {
            self::text('Archivo no encontrado', 404);
        }

        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . str_replace('"', '', $fileName) . '"');
        readfile($path);
        exit;
    }

    /** @param array<string, mixed> $data */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
