<?php

declare(strict_types=1);

namespace FinesApp\Core;

final class Request
{
    /** @param array<string, mixed> $get @param array<string, mixed> $post @param array<string, string> $server @param array<string, mixed> $files */
    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $server,
        private readonly array $files = [],
    ) {
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_SERVER, $_FILES);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(string $basePath = ''): string
    {
        $uri = parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        return '/' . trim($uri, '/');
    }

    public function query(string $key, ?string $default = null): ?string
    {
        $value = $this->get[$key] ?? $default;
        return is_scalar($value) ? trim((string) $value) : $default;
    }

    public function input(string $key, ?string $default = null): ?string
    {
        $value = $this->post[$key] ?? $default;
        return is_scalar($value) ? trim((string) $value) : $default;
    }

    /** @return array<int|string, mixed> */
    public function arrayInput(string $key): array
    {
        $value = $this->post[$key] ?? [];
        return is_array($value) ? $value : [];
    }

    public function checkbox(string $key): int
    {
        return isset($this->post[$key]) ? 1 : 0;
    }

    /** @return array{name:string,type:string,tmp_name:string,error:int,size:int}|null */
    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;
        if (!is_array($file) || !isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return [
            'name' => (string) ($file['name'] ?? ''),
            'type' => (string) ($file['type'] ?? ''),
            'tmp_name' => (string) ($file['tmp_name'] ?? ''),
            'error' => (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE),
            'size' => (int) ($file['size'] ?? 0),
        ];
    }

    public function isHtmx(): bool
    {
        return strtolower($this->server['HTTP_HX_REQUEST'] ?? '') === 'true';
    }
}
