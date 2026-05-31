<?php

declare(strict_types=1);

namespace FinesApp\Core;

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

    /** @return array{host:string, port:string, database:string, username:string, password:string, charset:string} */
    public function database(): array
    {
        return [
            'host' => $this->string('DB_HOST', '127.0.0.1'),
            'port' => $this->string('DB_PORT', '3306'),
            'database' => $this->string('DB_DATABASE', 'planfi10_20204'),
            'username' => $this->string('DB_USERNAME', 'root'),
            'password' => $this->string('DB_PASSWORD', ''),
            'charset' => $this->string('DB_CHARSET', 'utf8mb3'),
        ];
    }
}
