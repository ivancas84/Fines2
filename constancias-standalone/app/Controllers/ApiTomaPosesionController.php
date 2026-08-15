<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Request;
use ConstanciasApp\Core\Response;
use ConstanciasApp\Services\TomaPosesionService;

/**
 * API interna para que fines-standalone genere tomas de posesión.
 * Autenticación por API key (header X-Api-Key o body api_key).
 */
final class ApiTomaPosesionController extends Controller
{
    public function create(Request $request, array $vars = []): void
    {
        try {
            $this->assertApiKey($request);

            $payload = $request->json();
            if ($payload === []) {
                // Fallback form-encoded
                $payload = [
                    'docente' => [],
                    'cargo' => [],
                    'enviar_email' => $request->input('enviar_email') === '1',
                ];
            }

            $enviarEmail = !empty($payload['enviar_email']);
            $result = (new TomaPosesionService($this->pdo, $this->config))->generate($payload, $enviarEmail);

            Response::json([
                'ok' => true,
                'data' => $result,
            ]);
        } catch (\InvalidArgumentException $exception) {
            Response::json(['ok' => false, 'error' => $exception->getMessage()], 422);
        } catch (\Throwable $exception) {
            $message = $this->config->bool('APP_DEBUG', false)
                ? $exception->getMessage()
                : 'Error al generar la toma de posesión.';
            Response::json(['ok' => false, 'error' => $message], 500);
        }
    }

    private function assertApiKey(Request $request): void
    {
        $configured = trim($this->config->string('CONSTANCIAS_API_KEY', ''));
        if ($configured === '') {
            throw new \RuntimeException('CONSTANCIAS_API_KEY no está configurada en constancias-standalone.');
        }

        $provided = trim((string) (
            $request->header('X-Api-Key')
            ?? $request->json()['api_key']
            ?? $request->input('api_key')
            ?? ''
        ));

        if ($provided === '' || !hash_equals($configured, $provided)) {
            Response::json(['ok' => false, 'error' => 'API key inválida.'], 401);
        }
    }
}
