<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

final class Config
{
    public function __construct(private readonly string $rootPath)
    {
    }

    public function rootPath(): string
    {
        return $this->rootPath;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = strtolower($this->string($key, $default ? 'true' : 'false'));
        return in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    public function basePath(): string
    {
        $basePath = rtrim($this->string('APP_BASE_PATH', ''), '/');
        if ($basePath !== '') {
            return $basePath;
        }

        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        return rtrim(str_replace('\\', '/', dirname($script)), '/');
    }

    public function storagePath(): string
    {
        $path = $this->string('CONSTANCIAS_STORAGE_PATH', $this->rootPath . '/storage/constancias');
        return rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

    public function publicUrl(): string
    {
        $url = rtrim($this->string('CONSTANCIAS_PUBLIC_URL', ''), '/');
        if ($url !== '') {
            return $url;
        }

        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
        return rtrim(($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . url('/'), '/');
    }

    public function database(): array
    {
        return [
            'host' => $this->string('DB_HOST', '127.0.0.1'),
            'port' => $this->string('DB_PORT', '3306'),
            'database' => $this->string('DB_DATABASE', 'planfi10_20204'),
            'username' => $this->string('DB_USERNAME', 'root'),
            'password' => $this->string('DB_PASSWORD', ''),
            'charset' => $this->string('DB_CHARSET', 'utf8'),
        ];
    }
}
