<?php

declare(strict_types=1);

namespace FinesApp\Support;

/**
 * Nómina pegada desde Excel para cargar alumnos en una comisión.
 * Equivalente a ValueTypesUtils::excelParseIgnorePrefix de cac3.
 */
final class AlumnosComisionParser
{
    /** @var array<string, string> */
    private const FIELDS = [
        'nombres' => 'nombres',
        'apellidos' => 'apellidos',
        'cuil_dni' => 'documento',
        'dni_cuil' => 'documento',
        'cuil' => 'documento',
        'dni' => 'documento',
        'numero_documento' => 'documento',
        'fecha_nacimiento' => 'fecha_nacimiento',
        'anio_ingreso' => 'anio_ingreso',
        'modulo' => 'modulo',
        'observaciones' => 'observaciones',
        'tiene_certificado' => 'tiene_certificado',
        'tiene_constancia' => 'tiene_constancia',
        'tiene_dni' => 'tiene_dni',
        'tiene_partida' => 'tiene_partida',
        'previas_completas' => 'previas_completas',
    ];

    /**
     * @return list<array<string, string>>
     */
    public static function parse(string $rawData): array
    {
        $rawData = preg_replace('/^\xEF\xBB\xBF/', '', $rawData) ?? $rawData;
        $rawData = trim($rawData);
        if ($rawData === '') {
            throw new \InvalidArgumentException('Pegá la nómina copiada de Excel.');
        }

        $lines = preg_split("/\r\n|\n|\r/", $rawData) ?: [];
        $lines = array_values(array_filter(
            $lines,
            static fn (string $line): bool => trim($line) !== '',
        ));
        if ($lines === []) {
            throw new \InvalidArgumentException('Pegá la nómina copiada de Excel.');
        }

        $headers = array_map(
            static fn (string $cell): string => self::normalizeHeader($cell),
            explode("\t", $lines[0]),
        );
        $map = [];
        $hasDocumento = false;
        foreach ($headers as $index => $header) {
            if ($header === '' || str_starts_with($header, '_')) {
                continue;
            }
            $field = self::FIELDS[$header] ?? null;
            if ($field === null) {
                continue;
            }
            $map[$index] = $field;
            if ($field === 'documento') {
                $hasDocumento = true;
            }
        }
        if (!$hasDocumento) {
            throw new \InvalidArgumentException('Falta la columna cuil_dni (o dni_cuil).');
        }

        $rows = [];
        $count = count($lines);
        for ($line = 1; $line < $count; $line++) {
            $cells = array_map(trim(...), explode("\t", $lines[$line]));
            $row = [];
            $any = false;
            foreach ($map as $index => $field) {
                $value = trim((string) ($cells[$index] ?? ''));
                if ($value !== '') {
                    $any = true;
                }
                if (!array_key_exists($field, $row) || $value !== '') {
                    $row[$field] = $value;
                }
            }
            if ($any) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private static function normalizeHeader(string $header): string
    {
        $header = preg_replace('/^\xEF\xBB\xBF/', '', trim($header)) ?? trim($header);
        $header = preg_replace('/\*+$/u', '', $header) ?? $header;
        $header = mb_strtolower(trim($header), 'UTF-8');

        return str_replace([' ', '-'], '_', $header);
    }
}
