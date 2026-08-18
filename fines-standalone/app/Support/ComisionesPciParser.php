<?php

declare(strict_types=1);

namespace FinesApp\Support;

/**
 * Parsea el texto copiado del "Informe Global por Comisión PCI".
 *
 * Formato:
 *   N| Comisión: {pfid} | 1°C | Orientación: ...
 *   Área A
 *   Horario: Martes: 08:00 a 09:20; ...
 *   Docente: APELLIDO Nombres - DNI: 12345678
 *
 * Ignora encabezados repetidos por salto de página del PDF y une
 * líneas partidas de Área / Horario / Docente.
 */
final class ComisionesPciParser
{
    /** @var list<string> */
    private const HEADER_PATTERNS = [
        '/^la plata$/iu',
        '/^per[ií]odo/iu',
        '/^perÃ­odo/iu',
        '/^cargos vigentes/iu',
        '/^informe/iu',
        '/^global/iu',
        '/^p[aá]gina(\s|$)/iu',
        '/^\d+\s*(de|\/)\s*\d+$/iu',
        '/^\d+$/u',
        '/^\d+\|$/u',
        '/^cens\s*:/iu',
        '/^instituci/iu',
    ];

    /**
     * @return array{
     *   rows: list<array{
     *     pfid: string,
     *     tramo: ?string,
     *     orientacion: ?string,
     *     area: string,
     *     codigo: string,
     *     horario: string,
     *     sin_horarios: bool,
     *     docente_nombre: ?string,
     *     dni: ?string,
     *     sin_designar: bool
     *   }>,
     *   warnings: list<string>,
     *   lines_kept: int
     * }
     */
    public static function parse(string $rawData): array
    {
        $lines = self::normalizeLines($rawData);
        $rows = [];
        $warnings = [];

        $current = self::emptyBlock();

        $flushArea = static function () use (&$rows, &$warnings, &$current): void {
            if ($current['pfid'] === null || $current['area'] === null) {
                $current['area'] = null;
                $current['horario'] = '';
                $current['docente'] = '';
                return;
            }

            $row = self::buildRow($current);
            if ($row['dni'] === null && !$row['sin_designar']) {
                $warnings[] = sprintf(
                    'Comisión %s Área %s: sin DNI de docente.',
                    $current['pfid'],
                    $current['area'],
                );
            }
            $rows[] = $row;

            $current['area'] = null;
            $current['horario'] = '';
            $current['docente'] = '';
        };

        foreach ($lines as $line) {
            if (preg_match('/Comisi(?:ó|o)n:\s*(\d{3,})/iu', $line, $match) === 1) {
                $flushArea();
                $current['pfid'] = $match[1];
                $current['tramo'] = self::extractTramo($line);
                $current['orientacion'] = self::extractOrientacion($line);
                continue;
            }

            if (preg_match('/^(?:Área|Area|AREA)\s+([A-E])\b/u', $line, $match) === 1) {
                $flushArea();
                $current['area'] = strtoupper($match[1]);
                continue;
            }

            if (preg_match('/^Horario:\s*(.*)$/iu', $line, $match) === 1) {
                $current['horario'] = self::cleanHorario($match[1]);
                continue;
            }

            if (preg_match('/^Docente:\s*(.*)$/iu', $line, $match) === 1) {
                $current['docente'] = trim($match[1]);
                if (self::docenteCompleto($current['docente'])) {
                    $flushArea();
                }
                continue;
            }

            if ($current['docente'] !== '' && !self::docenteCompleto($current['docente'])) {
                $current['docente'] = trim($current['docente'] . ' ' . $line);
                if (self::docenteCompleto($current['docente'])) {
                    $flushArea();
                }
                continue;
            }

            if (
                $current['area'] !== null
                && $current['horario'] !== ''
                && self::looksLikeHorarioFragment($line)
            ) {
                $current['horario'] = self::cleanHorario($current['horario'] . ' ' . $line);
            }
        }

        $flushArea();

        return [
            'rows' => $rows,
            'warnings' => $warnings,
            'lines_kept' => count($lines),
        ];
    }

    /**
     * @return list<string>
     */
    public static function normalizeLines(string $rawData): array
    {
        $rawData = str_replace(["\xC2\xA0", "\xA0"], ' ', $rawData);
        $split = preg_split("/\r\n|\n|\r/", $rawData) ?: [];

        $kept = [];
        foreach ($split as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            if (self::isHeaderOrNoise($line)) {
                continue;
            }
            $kept[] = $line;
        }

        $joined = [];
        $count = count($kept);
        for ($i = 0; $i < $count; $i++) {
            $line = $kept[$i];
            $next = $kept[$i + 1] ?? null;

            if (
                $next !== null
                && preg_match('/^(?:Área|Area|AREA)$/u', $line) === 1
                && preg_match('/^[A-E]$/u', $next) === 1
            ) {
                $joined[] = $line . ' ' . $next;
                $i++;
                continue;
            }

            if (
                $next !== null
                && preg_match('/Comisi(?:ó|o)n:\s*$/iu', $line) === 1
                && preg_match('/\d{3,}/', $next) === 1
            ) {
                $joined[] = $line . ' ' . $next;
                $i++;
                continue;
            }

            if (
                $next !== null
                && preg_match('/^Horario:\s*$/iu', $line) === 1
            ) {
                $joined[] = rtrim($line, ':') . ': ' . $next;
                $i++;
                continue;
            }

            if (
                $next !== null
                && preg_match('/^Docente:\s*$/iu', $line) === 1
            ) {
                $joined[] = rtrim($line, ':') . ': ' . $next;
                $i++;
                continue;
            }

            if (
                $next !== null
                && preg_match('/^Docente:/iu', $line) === 1
                && !self::docenteCompleto(preg_replace('/^Docente:\s*/iu', '', $line) ?? $line)
                && !self::isKeywordLine($next)
            ) {
                $joined[] = $line . ' ' . $next;
                $i++;
                continue;
            }

            $joined[] = $line;
        }

        return $joined;
    }

    /**
     * @param array{
     *   pfid: ?string,
     *   tramo: ?string,
     *   orientacion: ?string,
     *   area: ?string,
     *   horario: string,
     *   docente: string
     * } $current
     * @return array{
     *   pfid: string,
     *   tramo: ?string,
     *   orientacion: ?string,
     *   area: string,
     *   codigo: string,
     *   horario: string,
     *   sin_horarios: bool,
     *   docente_nombre: ?string,
     *   dni: ?string,
     *   sin_designar: bool
     * }
     */
    private static function buildRow(array $current): array
    {
        $docente = trim($current['docente']);
        $sinDesignar = self::isSinDesignar($docente);
        $dni = $sinDesignar ? null : self::extractDni($docente);
        $nombre = null;
        if (!$sinDesignar && $docente !== '') {
            $nombre = trim((string) preg_replace('/\s*[-–]?\s*D\.?N\.?I\.?\s*:?\s*\d{7,8}\s*$/iu', '', $docente));
            $nombre = $nombre !== '' ? $nombre : null;
        }

        $horario = self::cleanHorario($current['horario']);
        $sinHorarios = $horario === '' || preg_match('/sin horarios/iu', $horario) === 1;

        return [
            'pfid' => (string) $current['pfid'],
            'tramo' => $current['tramo'],
            'orientacion' => $current['orientacion'],
            'area' => (string) $current['area'],
            'codigo' => 'AREA ' . $current['area'],
            'horario' => $sinHorarios ? 'Sin horarios' : $horario,
            'sin_horarios' => $sinHorarios,
            'docente_nombre' => $nombre,
            'dni' => $dni,
            'sin_designar' => $sinDesignar,
        ];
    }

    /**
     * @return array{
     *   pfid: ?string,
     *   tramo: ?string,
     *   orientacion: ?string,
     *   area: ?string,
     *   horario: string,
     *   docente: string
     * }
     */
    private static function emptyBlock(): array
    {
        return [
            'pfid' => null,
            'tramo' => null,
            'orientacion' => null,
            'area' => null,
            'horario' => '',
            'docente' => '',
        ];
    }

    private static function isHeaderOrNoise(string $line): bool
    {
        foreach (self::HEADER_PATTERNS as $pattern) {
            if (preg_match($pattern, $line) === 1) {
                return true;
            }
        }

        return false;
    }

    private static function isKeywordLine(string $line): bool
    {
        return preg_match('/^(?:Área|Area|AREA|Horario:|Docente:|Comisi)/u', $line) === 1;
    }

    private static function docenteCompleto(string $docente): bool
    {
        $docente = trim($docente);

        return $docente !== '' && (self::isSinDesignar($docente) || self::extractDni($docente) !== null);
    }

    private static function isSinDesignar(string $docente): bool
    {
        return preg_match('/sin\s+designar/iu', $docente) === 1;
    }

    private static function extractDni(string $text): ?string
    {
        if (preg_match('/D\.?N\.?I\.?\s*:?\s*(\d{7,8})/iu', $text, $match) !== 1) {
            return null;
        }

        return $match[1];
    }

    private static function extractTramo(string $line): ?string
    {
        if (preg_match('/(\d)\s*[°ºo]?\s*C\b/u', $line, $match) !== 1) {
            return null;
        }

        return $match[1] . '°C';
    }

    private static function extractOrientacion(string $line): ?string
    {
        if (preg_match('/Orientaci(?:ó|o)n:\s*([^|]+)/iu', $line, $match) !== 1) {
            return null;
        }

        $value = trim($match[1]);

        return $value !== '' ? $value : null;
    }

    private static function cleanHorario(string $horario): string
    {
        $horario = trim($horario);
        $horario = preg_replace('/\s+/', ' ', $horario) ?? $horario;
        $horario = trim($horario, " \t;");

        return $horario;
    }

    private static function looksLikeHorarioFragment(string $line): bool
    {
        if (self::isKeywordLine($line)) {
            return false;
        }

        return preg_match(
            '/^(?:Lunes|Martes|Mi[eé]rcoles|Jueves|Viernes|S[aá]bado|Domingo|\d{1,2}:\d{2}|a\s+\d{1,2}:\d{2})/iu',
            $line,
        ) === 1;
    }
}
