<?php

declare(strict_types=1);

namespace ConstanciasApp\Core;

final class Request
{
    /** @param array<string, mixed> $json */
    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $server,
        private readonly array $files = [],
        private readonly array $json = [],
    ) {
    }

    public static function capture(): self
    {
        $json = [];
        $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '');
        if (str_contains(strtolower($contentType), 'application/json')) {
            $raw = file_get_contents('php://input');
            if (is_string($raw) && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $json = $decoded;
                }
            }
        }

        return new self($_GET, $_POST, $_SERVER, $_FILES, $json);
    }

    /** @return array<string, mixed> */
    public function json(): array
    {
        return $this->json;
    }

    public function header(string $name, ?string $default = null): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        $value = $this->server[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
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
}
