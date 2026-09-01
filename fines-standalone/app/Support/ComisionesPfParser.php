<?php

declare(strict_types=1);

namespace FinesApp\Support;

/**
 * Parsea el texto copiado del "Informe Global por Comisión" de ProgramaFines.
 *
 * Formato actual (código pegado al nombre, sin espacio):
 *   10166/WPVEducacion Artistica 3 Miercoles 19:40 a 21:00
 *
 * Formato anterior (código separado):
 *   10166/WPV Educacion Artistica 3 Miercoles 19:40 a 21:00
 *
 * A veces el PDF parte el curso en dos líneas:
 *   10333/WLL
 *    Lunes 21:20 a 22:40
 */
final class ComisionesPfParser
{
    /** @var list<string> */
    private const DIAS = [
        'Lunes',
        'Martes',
        'Miercoles',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sabado',
        'Sábado',
        'Domingo',
    ];

    /**
     * PFID + código de asignatura.
     * El código son 2–6 mayúsculas (WPV, WIF, ARTE) con dígitos opcionales (ARTE1),
     * y termina antes de un espacio o del nombre en Title Case (Educacion, no Ed).
     */
    private const HEADER_REGEX = '/(\d+)\s*\/\s*([A-Z]{2,6}\d*)(?=\s|[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]|$)/u';

    /**
     * @return list<string>
     */
    public static function lines(string $rawData): array
    {
        $lines = preg_split("/\r\n|\n|\r/", $rawData) ?: [];
        $lines = array_values(array_filter(
            array_map(static fn (string $line): string => rtrim($line), $lines),
            static fn (string $line): bool => trim($line) !== '',
        ));

        return self::joinSplitCursoLines($lines);
    }

    /**
     * @return array{pfid: string, codigo: string, horario: string}|null
     */
    public static function parseCursoLine(string $line): ?array
    {
        $dia = self::findDia($line);
        if ($dia === null) {
            return null;
        }

        $header = self::parsePfidCodigo($line);
        if ($header === null) {
            return null;
        }

        $diaPos = self::diaPosition($line, $dia);
        if ($diaPos === null) {
            return null;
        }
        $horario = trim(substr($line, $diaPos));
        if ($horario === '') {
            return null;
        }

        return [
            'pfid' => $header['pfid'],
            'codigo' => $header['codigo'],
            'horario' => $horario,
        ];
    }

    /**
     * @return array{pfid: string, codigo: string}|null
     */
    public static function parsePfidCodigo(string $line): ?array
    {
        if (preg_match(self::HEADER_REGEX, $line, $matches) !== 1) {
            return null;
        }

        $pfid = trim($matches[1]);
        $codigo = trim($matches[2]);
        if ($pfid === '' || $codigo === '') {
            return null;
        }

        return [
            'pfid' => $pfid,
            'codigo' => $codigo,
        ];
    }

    public static function findDia(string $line): ?string
    {
        foreach (self::DIAS as $dia) {
            if (str_contains($line, $dia)) {
                return $dia;
            }
        }

        return null;
    }

    /**
     * Une `{pfid}/{codigo}` (sin día) con la línea de horario siguiente.
     * Omite líneas de nombre de asignatura intercaladas por el PDF.
     *
     * @param list<string> $lines
     * @return list<string>
     */
    private static function joinSplitCursoLines(array $lines): array
    {
        $out = [];
        $count = count($lines);

        for ($i = 0; $i < $count; $i++) {
            $line = $lines[$i];
            if (self::findDia($line) !== null) {
                $out[] = $line;
                continue;
            }

            $header = self::parsePfidCodigo($line);
            if ($header === null) {
                $out[] = $line;
                continue;
            }

            $horarioLine = null;
            $horarioIndex = null;
            for ($j = $i + 1; $j < $count; $j++) {
                $candidate = $lines[$j];
                if (self::parsePfidCodigo($candidate) !== null) {
                    break;
                }
                if (str_contains($candidate, '*') || preg_match('/\d{2}-\d{8}-\d/', $candidate) === 1) {
                    break;
                }
                if (self::findDia($candidate) !== null) {
                    $horarioLine = $candidate;
                    $horarioIndex = $j;
                    break;
                }
            }

            if ($horarioLine !== null && $horarioIndex !== null) {
                $out[] = trim($line) . ' ' . trim($horarioLine);
                $i = $horarioIndex;
                continue;
            }

            $out[] = $line;
        }

        return $out;
    }

    private static function diaPosition(string $line, string $dia): ?int
    {
        $pos = strpos($line, $dia);

        return $pos === false ? null : $pos;
    }
}
