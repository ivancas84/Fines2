<?php

declare(strict_types=1);

namespace FinesApp\Support;

/**
 * Utilidades de comparación de nombres de persona.
 * Basado en Fines2\Model\Persona_::nombreParecido.
 */
final class PersonaName
{
    /**
     * Determina si dos personas tienen nombres/apellidos parecidos.
     *
     * Lineamiento (igual que Persona_::nombreParecido):
     * - Normaliza nombres y apellidos a minúsculas.
     * - Tokeniza por espacios (nombres + apellidos).
     * - Considera parecido si algún token de $persona1 tiene un prefijo de
     *   $length caracteres que coincide con el inicio de algún token de $persona2.
     *
     * @param array{nombres?: mixed, apellidos?: mixed} $persona1 Registro de referencia (p. ej. BD)
     * @param array{nombres?: mixed, apellidos?: mixed} $persona2 Datos a comparar (p. ej. planilla)
     */
    public static function nombreParecido(array $persona1, array $persona2, int $length = 5): bool
    {
        // Obtener y normalizar los nombres y apellidos a minúsculas
        $nombres1 = isset($persona1['nombres']) ? mb_strtolower((string) $persona1['nombres']) : '';
        $apellidos1 = isset($persona1['apellidos']) ? mb_strtolower((string) $persona1['apellidos']) : '';
        $nombres2 = isset($persona2['nombres']) ? mb_strtolower((string) $persona2['nombres']) : '';
        $apellidos2 = isset($persona2['apellidos']) ? mb_strtolower((string) $persona2['apellidos']) : '';

        // Convertir a arrays de palabras
        $tokens1 = array_merge(explode(' ', $nombres1), explode(' ', $apellidos1));
        $tokens2 = array_merge(explode(' ', $nombres2), explode(' ', $apellidos2));

        // Recorrer todas las combinaciones posibles
        foreach ($tokens1 as $token1) {
            $token1Prefix = mb_substr($token1, 0, $length);
            // Prefijo vacío haría match con cualquier token (mb_strpos($x, '') === 0).
            if ($token1Prefix === '') {
                continue;
            }
            foreach ($tokens2 as $token2) {
                if ($token2 === '') {
                    continue;
                }
                if (mb_strpos($token2, $token1Prefix) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @param array{nombres?: mixed, apellidos?: mixed, numero_documento?: mixed} $persona */
    public static function label(array $persona): string
    {
        $nombre = trim(implode(' ', array_filter([
            trim((string) ($persona['apellidos'] ?? '')),
            trim((string) ($persona['nombres'] ?? '')),
        ])));
        $dni = preg_replace('/\D+/', '', (string) ($persona['numero_documento'] ?? '')) ?? '';
        if ($nombre === '') {
            return $dni !== '' ? 'DNI ' . $dni : 'sin datos';
        }

        return $dni !== '' ? $nombre . ' (DNI ' . $dni . ')' : $nombre;
    }
}
