<?php

declare(strict_types=1);

namespace FinesApp\Support;

final class PlanillaCalificacionParser
{
    public const FORMATS = ['PF2', 'XLSX', 'PF'];

    /**
     * @param array<string, mixed> $row
     * @return array{nombres: string, apellidos: string, numero_documento: string, nota: int}
     */
    public static function parse(array $row, string $format): array
    {
        return match (strtoupper($format)) {
            'PF' => self::parsePf($row),
            'PF2' => self::parsePf2($row),
            'XLSX' => self::parseXlsx($row),
            default => throw new \InvalidArgumentException('Formato no reconocido.'),
        };
    }

    /**
     * @param array<string, mixed> $row
     * @return array{nombres: string, apellidos: string, numero_documento: string, nota: int}
     */
    public static function parseXlsx(array $row): array
    {
        $data = [
            'nombres' => '',
            'apellidos' => '',
            'numero_documento' => '',
            'nota' => 0,
        ];

        foreach ($row as $key => $value) {
            $k = mb_strtolower((string) $key);
            $value = trim((string) ($value ?? ''));

            if (str_contains($k, 'nom')) {
                $data['nombres'] = $value;
            } elseif (str_contains($k, 'ape')) {
                $data['apellidos'] = $value;
            } elseif (str_contains($k, 'dni') || str_contains($k, 'doc')) {
                $data['numero_documento'] = ExcelParser::cleanDigits($value);
            } elseif (str_contains((string) $key, 'final') || str_contains($k, 'nota') || str_contains($k, 'calif')) {
                $digits = ExcelParser::cleanDigits($value);
                $data['nota'] = $digits === '' ? 0 : (int) $digits;
            }
        }

        if ($data['nombres'] === '' || $data['apellidos'] === '' || $data['numero_documento'] === '' || $data['nota'] === 0) {
            throw new \RuntimeException('Datos incompletos en la fila.');
        }

        if ($data['nota'] < 7) {
            throw new \RuntimeException('Calificación vacía o menor a 7.');
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $row
     * @return array{nombres: string, apellidos: string, numero_documento: string, nota: int}
     */
    public static function parsePf(array $row): array
    {
        $data = [
            'nombres' => '',
            'apellidos' => '',
            'numero_documento' => '',
            'nota' => 0,
        ];

        foreach ($row as $key => $value) {
            $key = (string) $key;
            if (str_contains($key, 'Nombre')) {
                $parsed = self::parseFirstColumnPf((string) $value);
                if ($parsed === null) {
                    throw new \RuntimeException('No se pudo interpretar la columna Nombre.');
                }
                $data = array_merge($data, $parsed);
            } elseif (str_contains($key, 'Final')) {
                $nota = (int) trim((string) $value);
                if ($nota < 7) {
                    throw new \RuntimeException('Calificación vacía o menor a 7.');
                }
                $data['nota'] = $nota;
            }
        }

        if ($data['nombres'] === '' || $data['apellidos'] === '' || $data['nota'] === 0) {
            throw new \RuntimeException('Datos incompletos en la fila.');
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $row
     * @return array{nombres: string, apellidos: string, numero_documento: string, nota: int}
     */
    public static function parsePf2(array $row): array
    {
        if (empty($row['DNI'])) {
            throw new \RuntimeException('DNI vacío o inexistente.');
        }

        $dni = ExcelParser::cleanDigits((string) $row['DNI']);
        if ($dni === '' || (int) $dni <= 0) {
            throw new \RuntimeException('DNI inválido.');
        }

        if (empty($row['Alumno'])) {
            throw new \RuntimeException('Alumno vacío.');
        }

        $alumno = trim((string) $row['Alumno']);
        if (!str_contains($alumno, ',')) {
            throw new \RuntimeException("Formato de alumno inválido: se espera 'APELLIDO, Nombre'.");
        }

        [$apellidos, $nombres] = array_map('trim', explode(',', $alumno, 2));
        if ($apellidos === '' || $nombres === '') {
            throw new \RuntimeException('Nombre o apellido incompleto.');
        }

        if (!isset($row['Promedio']) || trim((string) $row['Promedio']) === '') {
            throw new \RuntimeException('Promedio vacío.');
        }

        $nota = (int) trim((string) $row['Promedio']);
        if ($nota < 7) {
            throw new \RuntimeException('Calificación menor a 7.');
        }

        return [
            'numero_documento' => $dni,
            'apellidos' => $apellidos,
            'nombres' => $nombres,
            'nota' => $nota,
        ];
    }

    /**
     * @return array{apellidos: string, nombres: string, numero_documento: string}|null
     */
    private static function parseFirstColumnPf(string $inputString): ?array
    {
        $spacePos = strpos($inputString, ' ');
        if ($spacePos === false) {
            return null;
        }

        $inputString = substr($inputString, $spacePos + 1);
        $parts = explode('DNI', $inputString);
        if (count($parts) !== 2) {
            return null;
        }

        $namePart = trim($parts[0]);
        $dniPart = trim($parts[1]);
        if (!is_numeric($dniPart)) {
            return null;
        }

        $nameSplit = explode(',', $namePart);
        if (count($nameSplit) !== 2) {
            return null;
        }

        $apellidos = trim($nameSplit[0]);
        $nombres = trim($nameSplit[1]);
        if ($apellidos === '' || $nombres === '') {
            return null;
        }

        return [
            'apellidos' => $apellidos,
            'nombres' => $nombres,
            'numero_documento' => $dniPart,
        ];
    }

}
