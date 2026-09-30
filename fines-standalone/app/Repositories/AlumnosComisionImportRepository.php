<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use FinesApp\Support\AlumnosComisionParser;
use FinesApp\Support\PersonaName;
use PDO;

/**
 * Carga de alumnos en una comisión desde una nómina de Excel.
 * Equivalente a wp/cac3_cargar_alumnos_comision.
 */
final class AlumnosComisionImportRepository
{
    /** @var list<string> */
    private const PERSONA_COLUMNS = [
        'nombres',
        'apellidos',
        'numero_documento',
        'cuil',
        'cuil1',
        'cuil2',
        'fecha_nacimiento',
        'dia_nacimiento',
        'mes_nacimiento',
        'anio_nacimiento',
        'nacionalidad',
    ];

    /** @var list<string> */
    private const ALUMNO_COLUMNS = [
        'plan',
        'anio_ingreso',
        'semestre_ingreso',
        'confirmado_direccion',
        'observaciones',
        'tiene_certificado',
        'tiene_constancia',
        'tiene_dni',
        'tiene_partida',
        'previas_completas',
    ];

    /** @var list<string> */
    private const BOOL_FIELDS = [
        'tiene_certificado',
        'tiene_constancia',
        'tiene_dni',
        'tiene_partida',
        'previas_completas',
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{
     *   rows_total: int,
     *   procesados: int,
     *   errores: int,
     *   personas_insertadas: int,
     *   personas_actualizadas: int,
     *   alumnos_insertados: int,
     *   alumnos_actualizados: int,
     *   vinculos_ingresante: int,
     *   vinculos_incorporado: int,
     *   ya_en_comision: int,
     *   log: list<array{level: string, message: string}>
     * }
     */
    public function import(string $comisionId, string $rawData): array
    {
        $comisionId = trim($comisionId);
        $planId = $this->planId($comisionId);
        if ($planId === null) {
            throw new \InvalidArgumentException('No se encontró la comisión.');
        }
        if ($planId === '') {
            throw new \InvalidArgumentException('La comisión no tiene plan. No se pueden cargar alumnos.');
        }

        $rows = AlumnosComisionParser::parse($rawData);
        $report = [
            'rows_total' => count($rows),
            'procesados' => 0,
            'errores' => 0,
            'personas_insertadas' => 0,
            'personas_actualizadas' => 0,
            'alumnos_insertados' => 0,
            'alumnos_actualizados' => 0,
            'vinculos_ingresante' => 0,
            'vinculos_incorporado' => 0,
            'ya_en_comision' => 0,
            'log' => [],
        ];

        $dnis = [];
        foreach ($rows as $index => $row) {
            $numero = $index + 1;
            $documento = $this->cuilDni((string) ($row['documento'] ?? ''));
            $label = trim(implode(' ', array_filter([
                trim((string) ($row['apellidos'] ?? '')),
                trim((string) ($row['nombres'] ?? '')),
                $documento['dni'],
            ])));
            $report['log'][] = ['level' => 'info', 'message' => 'Alumno: ' . $numero];
            if ($label !== '') {
                $report['log'][] = ['level' => 'info', 'message' => $label];
            }

            if ($documento['dni'] === '') {
                $report['errores']++;
                $report['log'][] = ['level' => 'error', 'message' => 'DNI vacío, no se procesará el alumno.'];
                continue;
            }
            if (isset($dnis[$documento['dni']])) {
                $report['errores']++;
                $report['log'][] = ['level' => 'error', 'message' => 'DNI ya procesado, no se procesará el alumno.'];
                continue;
            }
            $dnis[$documento['dni']] = true;

            try {
                $this->pdo->beginTransaction();
                $outcome = $this->processRow($comisionId, $planId, $row, $documento);
                $this->pdo->commit();
            } catch (\Throwable $throwable) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                $report['errores']++;
                $report['log'][] = ['level' => 'error', 'message' => $throwable->getMessage()];
                continue;
            }

            foreach ($outcome['log'] as $entry) {
                $report['log'][] = $entry;
            }
            $report['procesados']++;
            $report['log'][] = ['level' => 'success', 'message' => 'Finalizado'];
            if ($outcome['persona'] === 'insertada') {
                $report['personas_insertadas']++;
            } elseif ($outcome['persona'] === 'actualizada') {
                $report['personas_actualizadas']++;
            }
            if ($outcome['alumno'] === 'insertado') {
                $report['alumnos_insertados']++;
            } elseif ($outcome['alumno'] === 'actualizado') {
                $report['alumnos_actualizados']++;
            }
            if ($outcome['vinculo'] === 'ingresante') {
                $report['vinculos_ingresante']++;
            } elseif ($outcome['vinculo'] === 'incorporado') {
                $report['vinculos_incorporado']++;
            } else {
                $report['ya_en_comision']++;
            }
        }

        return $report;
    }

    private function planId(string $comisionId): ?string
    {
        $stmt = $this->pdo->prepare('
            SELECT comision.id,
                   planificacion.plan AS plan_id
            FROM comision
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            WHERE comision.id = :id
            LIMIT 1
        ');
        $stmt->execute(['id' => $comisionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return trim((string) ($row['plan_id'] ?? ''));
    }

    /**
     * @param array<string, string> $row
     * @param array{dni: string, cuil: string, cuil1: ?int, cuil2: ?int} $documento
     * @return array{
     *   persona: string,
     *   alumno: string,
     *   vinculo: string,
     *   log: list<array{level: string, message: string}>
     * }
     */
    private function processRow(string $comisionId, string $planId, array $row, array $documento): array
    {
        $log = [];
        $persona = $this->findPersona($documento['dni'], $documento['cuil']);
        $personaChanges = [];
        $nombres = trim((string) ($row['nombres'] ?? ''));
        $apellidos = trim((string) ($row['apellidos'] ?? ''));

        if ($persona === null) {
            if ($nombres === '') {
                throw new \InvalidArgumentException('Falta el nombre. No se puede crear la persona.');
            }
            $personaId = uniqid();
            $fecha = $this->fechaNacimiento($row['fecha_nacimiento'] ?? '');
            $this->pdo->prepare('
                INSERT INTO persona (
                    id, nombres, apellidos, numero_documento, cuil, cuil1, cuil2,
                    fecha_nacimiento, dia_nacimiento, mes_nacimiento, anio_nacimiento, nacionalidad
                ) VALUES (
                    :id, :nombres, :apellidos, :numero_documento, :cuil, :cuil1, :cuil2,
                    :fecha_nacimiento, :dia_nacimiento, :mes_nacimiento, :anio_nacimiento, :nacionalidad
                )
            ')->execute([
                'id' => $personaId,
                'nombres' => $nombres,
                'apellidos' => $apellidos !== '' ? $apellidos : null,
                'numero_documento' => $documento['dni'],
                'cuil' => $documento['cuil'] !== '' ? $documento['cuil'] : null,
                'cuil1' => $documento['cuil1'],
                'cuil2' => $documento['cuil2'],
                'fecha_nacimiento' => $fecha,
                'dia_nacimiento' => $fecha !== null ? (int) substr($fecha, 8, 2) : null,
                'mes_nacimiento' => $fecha !== null ? (int) substr($fecha, 5, 2) : null,
                'anio_nacimiento' => $fecha !== null ? (int) substr($fecha, 0, 4) : null,
                'nacionalidad' => 'Argentina',
            ]);
            $personaEstado = 'insertada';
            $log[] = ['level' => 'success', 'message' => 'Persona insertada'];
        } else {
            $personaId = (string) $persona['id'];
            if (!PersonaName::nombreParecido($persona, [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
            ])) {
                throw new \RuntimeException(
                    'Los nombres no son parecidos al registro almacenado: ' . PersonaName::label($persona),
                );
            }
            if ($nombres !== '' && $nombres !== (string) ($persona['nombres'] ?? '')) {
                $personaChanges['nombres'] = $nombres;
            }
            if ($apellidos !== '' && $apellidos !== (string) ($persona['apellidos'] ?? '')) {
                $personaChanges['apellidos'] = $apellidos;
            }
            if ($documento['dni'] !== (string) ($persona['numero_documento'] ?? '')) {
                $personaChanges['numero_documento'] = $documento['dni'];
            }
            if ($documento['cuil'] !== '' && $documento['cuil'] !== (string) ($persona['cuil'] ?? '')) {
                $personaChanges['cuil'] = $documento['cuil'];
                $personaChanges['cuil1'] = $documento['cuil1'];
                $personaChanges['cuil2'] = $documento['cuil2'];
            }
            $fecha = $this->fechaNacimiento($row['fecha_nacimiento'] ?? '');
            if ($fecha !== null) {
                $actualFecha = substr((string) ($persona['fecha_nacimiento'] ?? ''), 0, 10);
                if ($actualFecha !== $fecha) {
                    $personaChanges['fecha_nacimiento'] = $fecha;
                    $personaChanges['dia_nacimiento'] = (int) substr($fecha, 8, 2);
                    $personaChanges['mes_nacimiento'] = (int) substr($fecha, 5, 2);
                    $personaChanges['anio_nacimiento'] = (int) substr($fecha, 0, 4);
                }
            }
            if (trim((string) ($persona['nacionalidad'] ?? '')) === '') {
                $personaChanges['nacionalidad'] = 'Argentina';
            }
            if ($personaChanges === []) {
                $personaEstado = 'existente';
                $log[] = ['level' => 'info', 'message' => 'Persona existente'];
            } else {
                $this->updateColumns('persona', $personaId, $personaChanges, self::PERSONA_COLUMNS);
                $personaEstado = 'actualizada';
                $log[] = ['level' => 'success', 'message' => 'Persona actualizada'];
            }
        }

        $alumnoStmt = $this->pdo->prepare('
            SELECT id, plan, anio_ingreso, semestre_ingreso, confirmado_direccion, observaciones,
                   tiene_certificado, tiene_constancia, tiene_dni, tiene_partida, previas_completas
            FROM alumno
            WHERE persona = :persona
            LIMIT 1
        ');
        $alumnoStmt->execute(['persona' => $personaId]);
        $alumno = $alumnoStmt->fetch(PDO::FETCH_ASSOC);

        $alumnoChanges = [];
        $anioIngreso = null;
        $semestreIngreso = null;
        $confirmado = 0;
        $observaciones = null;
        $bools = array_fill_keys(self::BOOL_FIELDS, 0);

        if ($alumno === false) {
            $anioIngreso = $this->anioIngresoNuevo(null, (string) ($row['anio_ingreso'] ?? ''), $log);
            if ($anioIngreso !== null) {
                $confirmado = 1;
            }
            $semestreIngreso = $this->semestreIngreso($row, $log);
            $observaciones = $this->observacionesAlumno(null, (string) ($row['observaciones'] ?? ''), $log);
            foreach (self::BOOL_FIELDS as $field) {
                if (!array_key_exists($field, $row)) {
                    continue;
                }
                if ($this->toBool($row[$field])) {
                    $bools[$field] = 1;
                    $log[] = ['level' => 'success', 'message' => 'Se ha cargado el valor de ' . $field . '.'];
                }
            }
            $alumnoId = uniqid();
            $this->pdo->prepare('
                INSERT INTO alumno (
                    id, persona, plan, anio_ingreso, semestre_ingreso, confirmado_direccion, observaciones,
                    tiene_certificado, tiene_constancia, tiene_dni, tiene_partida, previas_completas
                ) VALUES (
                    :id, :persona, :plan, :anio_ingreso, :semestre_ingreso, :confirmado_direccion, :observaciones,
                    :tiene_certificado, :tiene_constancia, :tiene_dni, :tiene_partida, :previas_completas
                )
            ')->execute([
                'id' => $alumnoId,
                'persona' => $personaId,
                'plan' => $planId,
                'anio_ingreso' => $anioIngreso,
                'semestre_ingreso' => $semestreIngreso,
                'confirmado_direccion' => $confirmado,
                'observaciones' => $observaciones,
                'tiene_certificado' => $bools['tiene_certificado'],
                'tiene_constancia' => $bools['tiene_constancia'],
                'tiene_dni' => $bools['tiene_dni'],
                'tiene_partida' => $bools['tiene_partida'],
                'previas_completas' => $bools['previas_completas'],
            ]);
            $alumnoNuevo = true;
            $alumnoEstado = 'insertado';
            $log[] = ['level' => 'success', 'message' => 'Alumno insertado'];
        } else {
            $alumnoId = (string) $alumno['id'];
            $alumnoNuevo = false;
            if ((string) ($alumno['plan'] ?? '') !== $planId) {
                $alumnoChanges['plan'] = $planId;
                $log[] = ['level' => 'warning', 'message' => 'Se actualizó el plan del alumno.'];
            }
            $anioNuevo = $this->anioIngresoNuevo(
                $alumno['anio_ingreso'] !== null ? (string) $alumno['anio_ingreso'] : null,
                (string) ($row['anio_ingreso'] ?? ''),
                $log,
            );
            if ($anioNuevo !== null && $anioNuevo !== (string) ($alumno['anio_ingreso'] ?? '')) {
                $alumnoChanges['anio_ingreso'] = $anioNuevo;
                $alumnoChanges['confirmado_direccion'] = 1;
            }
            $semestre = $this->semestreIngreso($row, $log);
            if ($semestre !== null && (int) ($alumno['semestre_ingreso'] ?? 0) !== $semestre) {
                $alumnoChanges['semestre_ingreso'] = $semestre;
            }
            foreach (self::BOOL_FIELDS as $field) {
                if (!array_key_exists($field, $row)) {
                    continue;
                }
                $actual = (int) ($alumno[$field] ?? 0) === 1;
                $nuevo = $this->toBool($row[$field]);
                if ($actual && !$nuevo) {
                    $log[] = ['level' => 'warning', 'message' => 'ERROR: En el sistema tiene ' . $field . ' pero en la hoja de cálculo no.'];
                } elseif (!$actual && $nuevo) {
                    $alumnoChanges[$field] = 1;
                    $log[] = ['level' => 'success', 'message' => 'Se ha cargado el valor de ' . $field . '.'];
                }
            }
            $observaciones = $this->observacionesAlumno(
                $alumno['observaciones'] !== null ? (string) $alumno['observaciones'] : null,
                (string) ($row['observaciones'] ?? ''),
                $log,
            );
            if ($observaciones !== null && $observaciones !== (string) ($alumno['observaciones'] ?? '')) {
                $alumnoChanges['observaciones'] = $observaciones;
            }
            if ($alumnoChanges === []) {
                $alumnoEstado = 'existente';
                $log[] = ['level' => 'info', 'message' => 'Alumno existente'];
            } else {
                $this->updateColumns('alumno', $alumnoId, $alumnoChanges, self::ALUMNO_COLUMNS);
                $alumnoEstado = 'actualizado';
                $log[] = ['level' => 'success', 'message' => 'Alumno actualizado'];
            }
        }

        $vinculoStmt = $this->pdo->prepare('
            SELECT id
            FROM alumno_comision
            WHERE alumno = :alumno AND comision = :comision
            LIMIT 1
        ');
        $vinculoStmt->execute(['alumno' => $alumnoId, 'comision' => $comisionId]);
        $vinculoId = $vinculoStmt->fetchColumn();
        if ($vinculoId !== false) {
            $log[] = ['level' => 'info', 'message' => 'Alumno en comisión existente'];

            return [
                'persona' => $personaEstado,
                'alumno' => $alumnoEstado,
                'vinculo' => 'existente',
                'log' => $log,
            ];
        }

        // El alta nueva entra como Ingresante. Un alumno que ya existía, como Incorporado.
        // La observación de importación solo se guarda en el alta nueva, igual que cac3.
        $estado = $alumnoNuevo ? 'Ingresante' : 'Incorporado';
        $this->pdo->prepare('
            INSERT INTO alumno_comision (id, alumno, comision, estado, activo, observaciones)
            VALUES (:id, :alumno, :comision, :estado, 0, :observaciones)
        ')->execute([
            'id' => uniqid(),
            'alumno' => $alumnoId,
            'comision' => $comisionId,
            'estado' => $estado,
            'observaciones' => $alumnoNuevo ? 'Importado de lista de alumnos' : null,
        ]);
        $log[] = ['level' => 'success', 'message' => 'Alumno en comisión insertado (' . $estado . ')'];

        return [
            'persona' => $personaEstado,
            'alumno' => $alumnoEstado,
            'vinculo' => $alumnoNuevo ? 'ingresante' : 'incorporado',
            'log' => $log,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findPersona(string $dni, string $cuil): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT id, nombres, apellidos, numero_documento, cuil, cuil1, cuil2,
                   fecha_nacimiento, dia_nacimiento, mes_nacimiento, anio_nacimiento, nacionalidad
            FROM persona
            WHERE numero_documento = :dni
        ');
        $stmt->execute(['dni' => $dni]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $byId = [];
        foreach ($rows as $row) {
            $byId[(string) $row['id']] = $row;
        }
        if ($cuil !== '') {
            $cuilStmt = $this->pdo->prepare('
                SELECT id, nombres, apellidos, numero_documento, cuil, cuil1, cuil2,
                       fecha_nacimiento, dia_nacimiento, mes_nacimiento, anio_nacimiento, nacionalidad
                FROM persona
                WHERE cuil = :cuil
            ');
            $cuilStmt->execute(['cuil' => $cuil]);
            foreach ($cuilStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $byId[(string) $row['id']] = $row;
            }
        }
        if (count($byId) > 1) {
            throw new \RuntimeException('Hay más de una persona con ese DNI o CUIL.');
        }

        return $byId === [] ? null : array_values($byId)[0];
    }

    /**
     * @param list<array{level: string, message: string}> $log
     */
    private function anioIngresoNuevo(?string $actual, string $nuevo, array &$log): ?string
    {
        $nuevo = trim($nuevo);
        $tieneNuevo = $nuevo !== '';
        $tieneActual = $actual !== null && trim($actual) !== '';
        $anioActual = $tieneActual ? (int) substr(trim((string) $actual), 0, 1) : null;
        $anioNuevo = $tieneNuevo ? (int) substr($nuevo, 0, 1) : null;
        if ($tieneActual && $tieneNuevo && $anioActual > $anioNuevo) {
            $log[] = ['level' => 'warning', 'message' => 'ERROR: En el sistema el año ingreso es mayor al de la hoja de cálculo.'];

            return null;
        }
        if ($tieneNuevo && $anioActual !== $anioNuevo) {
            $log[] = ['level' => 'success', 'message' => 'Se ha cargado el valor de año ingreso.'];

            return $nuevo;
        }

        return null;
    }

    /**
     * @param array<string, string> $row
     * @param list<array{level: string, message: string}> $log
     */
    private function semestreIngreso(array $row, array &$log): ?int
    {
        if (!array_key_exists('modulo', $row) || substr(trim($row['modulo']), 0, 1) === '') {
            return null;
        }
        $modulo = (int) $row['modulo'];
        if ($modulo % 2 !== 0) {
            $log[] = ['level' => 'success', 'message' => 'Se ha asignado semestre ingreso = 1 (módulo impar).'];

            return 1;
        }
        $log[] = ['level' => 'success', 'message' => 'Se ha asignado semestre ingreso = 2 (módulo par).'];

        return 2;
    }

    /**
     * @param list<array{level: string, message: string}> $log
     */
    private function observacionesAlumno(?string $actual, string $nuevo, array &$log): ?string
    {
        $actual = trim((string) $actual);
        $nuevo = trim($nuevo);
        if (!$this->hayPalabrasNuevas($actual, $nuevo)) {
            $log[] = ['level' => 'info', 'message' => 'No se actualizará el valor de observaciones.'];

            return null;
        }
        $log[] = [
            'level' => 'success',
            'message' => 'Se actualizará el valor de observaciones. Antiguo = ' . $actual . '. Nuevo = ' . $nuevo,
        ];

        return $actual === '' ? $nuevo : $actual . ' - ' . $nuevo;
    }

    private function fechaNacimiento(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y'] as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $errors = \DateTime::getLastErrors();
            if (
                $date instanceof \DateTime
                && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))
            ) {
                return $date->format('Y-m-d');
            }
        }

        throw new \InvalidArgumentException('Fecha de nacimiento inválida. Usá aaaa-mm-dd.');
    }

    private function toBool(string $value): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }
        if (is_numeric($value)) {
            return (float) $value !== 0.0;
        }

        return in_array(strtolower(substr($value, 0, 1)), ['t', '1', 's', 'y', 'o'], true);
    }

    private function hayPalabrasNuevas(string $viejo, string $nuevo): bool
    {
        $viejas = $this->palabras($viejo);
        $nuevas = $this->palabras($nuevo);

        return array_diff($nuevas, $viejas) !== [];
    }

    /**
     * @return list<string>
     */
    private function palabras(string $texto): array
    {
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        $texto = strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
        ]);
        $texto = preg_replace('/[^a-z0-9\s]/u', '', $texto) ?? '';
        $texto = preg_replace('/\s+/u', ' ', $texto) ?? '';
        $palabras = array_values(array_filter(explode(' ', trim($texto)), static fn (string $word): bool => $word !== ''));

        return array_values(array_unique($palabras));
    }

    /**
     * @param array<string, mixed> $changes
     * @param list<string> $allowed
     */
    private function updateColumns(string $table, string $id, array $changes, array $allowed): void
    {
        $sets = [];
        $params = ['id' => $id];
        foreach ($changes as $column => $value) {
            if (!in_array($column, $allowed, true)) {
                throw new \InvalidArgumentException('Columna no permitida.');
            }
            $sets[] = $column . ' = :' . $column;
            $params[$column] = $value;
        }
        if ($sets === []) {
            return;
        }
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $this->pdo->prepare($sql)->execute($params);
    }

    /**
     * @return array{dni: string, cuil: string, cuil1: ?int, cuil2: ?int}
     */
    private function cuilDni(string $value): array
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        $result = ['dni' => '', 'cuil' => '', 'cuil1' => null, 'cuil2' => null];
        $length = strlen($digits);
        if ($length === 7 || $length === 8) {
            $result['dni'] = $digits;
        } elseif ($length === 11) {
            $result['cuil'] = $digits;
            $result['cuil1'] = (int) substr($digits, 0, 2);
            $result['dni'] = substr($digits, 2, 8);
            $result['cuil2'] = (int) substr($digits, 10, 1);
        }

        return $result;
    }
}
