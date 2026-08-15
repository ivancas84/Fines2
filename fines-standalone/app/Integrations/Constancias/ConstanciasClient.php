<?php

declare(strict_types=1);

namespace FinesApp\Integrations\Constancias;

use FinesApp\Core\Config;

/**
 * Cliente HTTP hacia constancias-standalone (API de toma de posesión).
 */
final class ConstanciasClient
{
    public function __construct(private readonly Config $config)
    {
    }

    /**
     * @param array<string, mixed> $payload
     * @return array{
     *   constancia_id: int,
     *   clave: string,
     *   validation_url: string,
     *   download_url: string,
     *   archivo_nombre: string,
     *   email_sent: bool,
     *   email_error: ?string
     * }
     */
    public function generarTomaPosesion(array $payload, bool $enviarEmail = false): array
    {
        $baseUrl = rtrim($this->config->string('CONSTANCIAS_PUBLIC_URL', ''), '/');
        $apiKey = trim($this->config->string('CONSTANCIAS_API_KEY', ''));
        if ($baseUrl === '') {
            throw new \RuntimeException('Falta configurar CONSTANCIAS_PUBLIC_URL.');
        }
        if ($apiKey === '') {
            throw new \RuntimeException('Falta configurar CONSTANCIAS_API_KEY (debe coincidir con constancias-standalone).');
        }

        $url = $baseUrl . '/api/toma-posesion';
        $body = array_merge($payload, [
            'enviar_email' => $enviarEmail,
            'api_key' => $apiKey,
        ]);

        $json = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('No se pudo serializar el payload de toma de posesión.');
        }

        $response = $this->postJson($url, $json, $apiKey);
        $decoded = json_decode($response['body'], true);
        if (!is_array($decoded)) {
            throw new \RuntimeException(
                'Respuesta inválida de constancias (HTTP ' . $response['status'] . ').',
            );
        }

        if (empty($decoded['ok'])) {
            $error = trim((string) ($decoded['error'] ?? 'Error desconocido en constancias.'));
            throw new \RuntimeException($error !== '' ? $error : 'Error al generar la toma en constancias.');
        }

        $data = is_array($decoded['data'] ?? null) ? $decoded['data'] : [];
        if ($data === []) {
            throw new \RuntimeException('Constancias no devolvió datos de la toma generada.');
        }

        return [
            'constancia_id' => (int) ($data['constancia_id'] ?? 0),
            'clave' => (string) ($data['clave'] ?? ''),
            'validation_url' => (string) ($data['validation_url'] ?? ''),
            'download_url' => (string) ($data['download_url'] ?? ''),
            'archivo_nombre' => (string) ($data['archivo_nombre'] ?? ''),
            'email_sent' => !empty($data['email_sent']),
            'email_error' => isset($data['email_error']) ? (string) $data['email_error'] : null,
        ];
    }

    /**
     * @return array{status: int, body: string}
     */
    private function postJson(string $url, string $json, string $apiKey): array
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                throw new \RuntimeException('No se pudo iniciar cURL hacia constancias.');
            }
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'X-Api-Key: ' . $apiKey,
                ],
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_TIMEOUT => 60,
            ]);
            $body = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            if ($body === false) {
                throw new \RuntimeException('Error de red al contactar constancias: ' . $error);
            }

            return ['status' => $status, 'body' => (string) $body];
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'X-Api-Key: ' . $apiKey,
                    'Content-Length: ' . strlen($json),
                ]),
                'content' => $json,
                'timeout' => 60,
                'ignore_errors' => true,
            ],
        ]);
        $body = file_get_contents($url, false, $context);
        if ($body === false) {
            throw new \RuntimeException('Error de red al contactar constancias.');
        }
        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m) === 1) {
            $status = (int) $m[1];
        }

        return ['status' => $status, 'body' => $body];
    }
}
