<?php

declare(strict_types=1);

namespace FinesApp\Integrations\ProgramaFines;

final class ProgramaFinesMapper
{
    public static function localPersona(array $persona, int $periodo, ?string $pfid = null): array
    {
        $sexo = (int) ($persona['sexo'] ?? 0);
        $sexo = in_array($sexo, [1, 2, 3], true) ? $sexo : 2;

        $data = [
            'mi_periodo' => $periodo,
            'apellido' => trim((string) ($persona['apellidos'] ?? '')),
            'nombre' => trim((string) ($persona['nombres'] ?? '')),
            'cuil1' => trim((string) ($persona['cuil1'] ?? '0')) ?: '0',
            'dni_cargar' => self::digits((string) ($persona['numero_documento'] ?? '')),
            'cuil2' => trim((string) ($persona['cuil2'] ?? '0')) ?: '0',
            'nacionalidad' => trim((string) ($persona['nacionalidad'] ?? '')) ?: 'Argentina',
            'sexo' => (string) $sexo,
            'dia_nac' => (string) ((int) ($persona['dia_nacimiento'] ?? 1) ?: 1),
            'mes_nac' => (string) ((int) ($persona['mes_nacimiento'] ?? 1) ?: 1),
            'ano_nac' => (string) ((int) ($persona['anio_nacimiento'] ?? 1999) ?: 1999),
            'direccion' => trim((string) ($persona['descripcion_domicilio'] ?? '')),
            'departamento' => trim((string) ($persona['departamento'] ?? '')),
            'localidad' => trim((string) ($persona['localidad'] ?? '')),
            'partido' => trim((string) ($persona['partido'] ?? '')),
            'email' => trim((string) ($persona['email'] ?? '')),
            'cod_area' => trim((string) ($persona['codigo_area'] ?? '')),
            'nro_telefono' => trim((string) ($persona['telefono'] ?? '')),
        ];

        if ($pfid !== null && trim($pfid) !== '') {
            $data['subcategory'] = trim($pfid);
        }

        return $data;
    }

    public static function differences(array $local, array $remote): array
    {
        $fields = [
            'apellidos' => ['local' => 'apellidos', 'remote' => 'apellido', 'label' => 'Apellidos'],
            'nombres' => ['local' => 'nombres', 'remote' => 'nombre', 'label' => 'Nombres'],
            'cuil1' => ['local' => 'cuil1', 'remote' => 'cuil1', 'label' => 'CUIL prefijo'],
            'cuil2' => ['local' => 'cuil2', 'remote' => 'cuil2', 'label' => 'CUIL sufijo'],
            'sexo' => ['local' => 'sexo', 'remote' => 'sexo', 'label' => 'Sexo'],
            'dia_nacimiento' => ['local' => 'dia_nacimiento', 'remote' => 'dia_nac', 'label' => 'Día de nacimiento'],
            'mes_nacimiento' => ['local' => 'mes_nacimiento', 'remote' => 'mes_nac', 'label' => 'Mes de nacimiento'],
            'anio_nacimiento' => ['local' => 'anio_nacimiento', 'remote' => 'ano_nac', 'label' => 'Año de nacimiento'],
            'descripcion_domicilio' => ['local' => 'descripcion_domicilio', 'remote' => 'direccion', 'label' => 'Domicilio'],
            'departamento' => ['local' => 'departamento', 'remote' => 'departamento', 'label' => 'Departamento'],
            'localidad' => ['local' => 'localidad', 'remote' => 'localidad', 'label' => 'Localidad'],
            'partido' => ['local' => 'partido', 'remote' => 'partido', 'label' => 'Partido'],
            'email' => ['local' => 'email', 'remote' => 'email', 'label' => 'Email'],
            'nacionalidad' => ['local' => 'nacionalidad', 'remote' => 'nacionalidad', 'label' => 'Nacionalidad'],
            'codigo_area' => ['local' => 'codigo_area', 'remote' => 'cod_area', 'label' => 'Código de área'],
            'telefono' => ['local' => 'telefono', 'remote' => 'nro_telefono', 'label' => 'Teléfono'],
        ];

        $differences = [];
        foreach ($fields as $field) {
            $localValue = self::normalize($local[$field['local']] ?? null);
            $remoteValue = self::normalize($remote[$field['remote']] ?? null);

            if ($localValue !== $remoteValue) {
                $differences[] = [
                    'label' => $field['label'],
                    'local' => self::display($local[$field['local']] ?? null),
                    'remote' => self::display($remote[$field['remote']] ?? null),
                ];
            }
        }

        return $differences;
    }

    public static function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    private static function normalize(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));
        if ($value === '') {
            return '';
        }

        $value = mb_strtolower($value, 'UTF-8');
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
        return preg_replace('/[^a-z0-9]+/', '', $value) ?? '';
    }

    private static function display(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));
        return $value !== '' ? $value : '—';
    }
}
