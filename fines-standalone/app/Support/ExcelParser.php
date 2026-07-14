<?php

declare(strict_types=1);

namespace FinesApp\Support;

final class ExcelParser
{
    /**
     * Parsea texto copiado desde Excel/Word (TSV).
     * Ignora columnas cuyo encabezado comienza con $ignore.
     *
     * @return list<array<string, string|null>>
     */
    public static function parseIgnorePrefix(string $rawData, string $ignore = '_'): array
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawData)) ?: [];
        if ($lines === []) {
            return [];
        }

        $rows = array_map(
            static fn (string $line): array => array_map('trim', explode("\t", $line)),
            $lines,
        );

        $headers = array_shift($rows);
        if (!is_array($headers) || $headers === []) {
            return [];
        }

        $validIndexes = [];
        $validHeaders = [];
        foreach ($headers as $index => $header) {
            if (!str_starts_with((string) $header, $ignore)) {
                $validIndexes[] = $index;
                $validHeaders[] = (string) $header;
            }
        }

        $result = [];
        foreach ($rows as $row) {
            $assocRow = [];
            foreach ($validIndexes as $i => $index) {
                $assocRow[$validHeaders[$i]] = $row[$index] ?? null;
            }
            $result[] = $assocRow;
        }

        return $result;
    }

    public static function cleanDigits(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value) ?? '';
    }
}
