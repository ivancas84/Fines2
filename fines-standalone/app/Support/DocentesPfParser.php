<?php

declare(strict_types=1);

namespace FinesApp\Support;

/**
 * Parsea texto copiado desde el XLSX de docentes de ProgramaFines.
 * Equivalente a ProgramaFines\Utils\PfUtils::excelDocentesParse.
 */
final class DocentesPfParser
{
    /**
     * @return list<array{
     *   nombres?: string,
     *   apellidos?: string,
     *   numero_documento?: string,
     *   descripcion_domicilio?: string,
     *   localidad?: string,
     *   fecha_nacimiento?: string,
     *   telefono?: string,
     *   email?: string,
     *   email_abc?: string,
     *   comision?: string,
     *   asignatura?: string,
     *   cens?: string,
     *   existente?: bool
     * }>
     */
    public static function parse(string $rawData): array
    {
        $rows = ExcelParser::parseIgnorePrefix($rawData);
        $dnisProcesados = [];
        $result = [];

        foreach ($rows as $row) {
            $data = [];

            foreach ($row as $key => $value) {
                $k = mb_strtolower(trim((string) $key));
                $value = $value === null ? null : trim((string) $value);
                if ($value === '') {
                    $value = null;
                }

                if ($k === 'nombre') {
                    $data['nombres'] = $value;
                } elseif (str_contains($k, 'apellido')) {
                    $data['apellidos'] = $value;
                } elseif (str_contains($k, 'dni')) {
                    $dni = ExcelParser::cleanDigits((string) ($value ?? ''));
                    $data['numero_documento'] = $dni !== '' ? $dni : null;
                    $data['existente'] = false;
                    if ($dni !== '' && in_array($dni, $dnisProcesados, true)) {
                        $data['existente'] = true;
                    } elseif ($dni !== '') {
                        $dnisProcesados[] = $dni;
                    }
                } elseif (str_contains($k, 'direccion')) {
                    $data['descripcion_domicilio'] = $value;
                } elseif (str_contains($k, 'localidad.')) {
                    $data['localidad'] = $value;
                } elseif (str_contains($k, 'localidad') && !isset($data['localidad'])) {
                    // Algunas planillas usan "Localidad" sin punto final.
                    $data['localidad'] = $value;
                } elseif (str_contains($k, 'fechanac')) {
                    $data['fecha_nacimiento'] = $value;
                } elseif (str_contains($k, 'celular') || str_contains($k, 'telefono') || str_contains($k, 'tel')) {
                    if (!isset($data['telefono']) || $data['telefono'] === null || $data['telefono'] === '') {
                        $data['telefono'] = $value;
                    }
                } elseif (str_contains($k, 'email')) {
                    $email = mb_strtolower((string) ($value ?? ''));
                    if (str_contains($email, '@abc')) {
                        $data['email_abc'] = $value;
                    } elseif (str_contains($email, '@')) {
                        $data['email'] = $value;
                    }
                } elseif (str_contains($k, 'comision')) {
                    $data['comision'] = $value;
                } elseif (str_contains($k, 'materia') || str_contains($k, 'asignatura')) {
                    $data['asignatura'] = $value;
                } elseif (str_contains($k, 'cens')) {
                    $data['cens'] = $value;
                }
            }

            $result[] = $data;
        }

        return $result;
    }

    public static function substringBetween(string $value, string $start, string $end): string
    {
        $posA = strpos($value, $start);
        if ($posA === false) {
            return '';
        }

        $after = substr($value, $posA + strlen($start));
        $posB = strpos($after, $end);
        if ($posB === false) {
            return '';
        }

        return substr($after, 0, $posB);
    }
}
