<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use FinesApp\Support\DocentesPfParser;
use FinesApp\Support\ExcelParser;
use FinesApp\Support\PersonaName;
use PDO;

/**
 * Importación de docentes desde planilla PF (migrado de wp/pfpd3_procesar_docentes_pf).
 */
final class DocentesPfImportRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{
     *   rows_total: int,
     *   docentes_insertados: int,
     *   docentes_existentes: int,
     *   docentes_modificados: int,
     *   docentes_diferentes: int,
     *   docentes_sin_designar: int,
     *   tomas_creadas: int,
     *   tomas_existentes_mismo: int,
     *   tomas_existentes_otro: int,
     *   errores: int,
     *   log: list<array{level: string, message: string, row?: int, cens?: string}>
     * }
     */
    public function process(string $rawData, string $calendarioId, string $censTomas = '462'): array
    {
        $calendarioId = trim($calendarioId);
        if ($calendarioId === '') {
            throw new \InvalidArgumentException('Seleccioná un calendario para procesar las tomas.');
        }
        $this->assertCalendarioExists($calendarioId);

        $censTomas = trim($censTomas);
        if ($censTomas === '') {
            $censTomas = '462';
        }

        $rows = DocentesPfParser::parse($rawData);
        $report = [
            'rows_total' => count($rows),
            'docentes_insertados' => 0,
            'docentes_existentes' => 0,
            'docentes_modificados' => 0,
            'docentes_diferentes' => 0,
            'docentes_sin_designar' => 0,
            'tomas_creadas' => 0,
            'tomas_existentes_mismo' => 0,
            'tomas_existentes_otro' => 0,
            'errores' => 0,
            'log' => [],
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;
            $cens = trim((string) ($row['cens'] ?? ''));
            $isCensTomas = $cens === $censTomas;

            try {
                $dni = ExcelParser::cleanDigits((string) ($row['numero_documento'] ?? ''));
                if ($dni === '') {
                    if ($isCensTomas) {
                        $this->log($report, 'warning', "Fila {$rowNumber}: docente sin designar (sin DNI).", $rowNumber, $cens);
                    } else {
                        $report['docentes_sin_designar']++;
                    }
                    continue;
                }

                $personaResult = $this->upsertPersona($row, $dni);
                if ($personaResult['action'] === 'insert') {
                    $report['docentes_insertados']++;
                    if ($isCensTomas) {
                        $this->log($report, 'success', "Fila {$rowNumber}: docente insertado (DNI {$dni}).", $rowNumber, $cens);
                    }
                } elseif ($personaResult['action'] === 'update') {
                    $report['docentes_modificados']++;
                    if ($isCensTomas) {
                        $this->log($report, 'info', "Fila {$rowNumber}: docente con datos vacíos completados (DNI {$dni}).", $rowNumber, $cens);
                    }
                } else {
                    $report['docentes_existentes']++;
                    if ($isCensTomas && ($personaResult['diffs'] ?? []) === []) {
                        $this->log($report, 'info', "Fila {$rowNumber}: docente existente (DNI {$dni}).", $rowNumber, $cens);
                    }
                }

                if (($personaResult['diffs'] ?? []) !== []) {
                    $report['docentes_diferentes']++;
                    $this->log(
                        $report,
                        'conflict',
                        "Fila {$rowNumber}: VERIFICAR persona DNI {$dni}. Datos distintos, no se actualizaron: "
                            . implode('; ', $personaResult['diffs']) . '.',
                        $rowNumber,
                        $cens !== '' ? $cens : null,
                    );
                }

                if (!$isCensTomas) {
                    continue;
                }

                $this->processTomasForRow(
                    $report,
                    $row,
                    $rowNumber,
                    $cens,
                    $personaResult['id'],
                    $calendarioId,
                );
            } catch (\Throwable $throwable) {
                $report['errores']++;
                $this->log(
                    $report,
                    'error',
                    "Fila {$rowNumber}: " . $throwable->getMessage(),
                    $rowNumber,
                    $cens !== '' ? $cens : null,
                );
            }
        }

        return $report;
    }

    /**
     * @param array<string, mixed> $row
     * @return array{id: string, action: 'insert'|'update'|'exists', diffs: list<string>}
     */
    private function upsertPersona(array $row, string $dni): array
    {
        $existing = $this->findPersonaByDni($dni);
        $payload = $this->personaPayload($row, $dni);

        if ($existing === null) {
            $id = uniqid();
            $nombres = trim((string) ($payload['nombres'] ?? ''));
            if ($nombres === '') {
                $nombres = '(sin nombre)';
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO persona (
                    id, nombres, apellidos, numero_documento, descripcion_domicilio, localidad,
                    telefono, email, email_abc, nacionalidad, fecha_nacimiento,
                    dia_nacimiento, mes_nacimiento, anio_nacimiento, genero, sexo
                ) VALUES (
                    :id, :nombres, :apellidos, :numero_documento, :descripcion_domicilio, :localidad,
                    :telefono, :email, :email_abc, :nacionalidad, :fecha_nacimiento,
                    :dia_nacimiento, :mes_nacimiento, :anio_nacimiento, :genero, :sexo
                )
            ");
            $stmt->execute([
                'id' => $id,
                'nombres' => $nombres,
                'apellidos' => $payload['apellidos'],
                'numero_documento' => $dni,
                'descripcion_domicilio' => $payload['descripcion_domicilio'],
                'localidad' => $payload['localidad'],
                'telefono' => $payload['telefono'],
                'email' => $payload['email'],
                'email_abc' => $payload['email_abc'],
                'nacionalidad' => $payload['nacionalidad'] ?? 'Argentina',
                'fecha_nacimiento' => $payload['fecha_nacimiento'],
                'dia_nacimiento' => $payload['dia_nacimiento'],
                'mes_nacimiento' => $payload['mes_nacimiento'],
                'anio_nacimiento' => $payload['anio_nacimiento'],
                'genero' => $payload['genero'],
                'sexo' => $payload['sexo'],
            ]);

            return ['id' => $id, 'action' => 'insert', 'diffs' => []];
        }

        $diffs = [];
        if (!PersonaName::nombreParecido($existing, $payload)) {
            $stored = trim(($existing['apellidos'] ?? '') . ', ' . ($existing['nombres'] ?? ''));
            $incoming = trim(($payload['apellidos'] ?? '') . ', ' . ($payload['nombres'] ?? ''));
            $diffs[] = "nombre «{$stored}» (BD) vs «{$incoming}» (planilla)";
        }

        $merged = $this->mergePersonaFields($existing, $payload);
        $diffs = array_merge($diffs, $merged['diffs']);
        if ($merged['changed']) {
            $stmt = $this->pdo->prepare("
                UPDATE persona
                SET nombres = :nombres,
                    apellidos = :apellidos,
                    descripcion_domicilio = :descripcion_domicilio,
                    localidad = :localidad,
                    telefono = :telefono,
                    email = :email,
                    email_abc = :email_abc,
                    nacionalidad = :nacionalidad,
                    fecha_nacimiento = :fecha_nacimiento,
                    dia_nacimiento = :dia_nacimiento,
                    mes_nacimiento = :mes_nacimiento,
                    anio_nacimiento = :anio_nacimiento,
                    genero = :genero,
                    sexo = :sexo
                WHERE id = :id
            ");
            $stmt->execute(array_merge($merged['data'], ['id' => $existing['id']]));

            return ['id' => (string) $existing['id'], 'action' => 'update', 'diffs' => $diffs];
        }

        return ['id' => (string) $existing['id'], 'action' => 'exists', 'diffs' => $diffs];
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, mixed> $report
     */
    private function processTomasForRow(
        array &$report,
        array $row,
        int $rowNumber,
        string $cens,
        string $personaId,
        string $calendarioId,
    ): void {
        $pfid = trim((string) ($row['comision'] ?? ''));
        if ($pfid === '') {
            $this->log($report, 'warning', "Fila {$rowNumber}: sin PFID de comisión.", $rowNumber, $cens);
            return;
        }

        $comisionId = $this->comisionIdByPfidAndCalendario($pfid, $calendarioId);
        if ($comisionId === null) {
            $this->log(
                $report,
                'warning',
                "Fila {$rowNumber}: no se encontró la comisión PFID {$pfid} en el calendario seleccionado.",
                $rowNumber,
                $cens,
            );
            return;
        }

        $cursos = $this->cursosConTomaActivaByComision($comisionId);
        if ($cursos === []) {
            $this->log(
                $report,
                'warning',
                "Fila {$rowNumber}: no hay cursos en la comisión {$comisionId}.",
                $rowNumber,
                $cens,
            );
            return;
        }

        $asignaturaLabel = trim((string) ($row['asignatura'] ?? ''));
        $codigo = trim(DocentesPfParser::substringBetween($asignaturaLabel, '(', ')'));
        if ($codigo === '') {
            $this->log(
                $report,
                'warning',
                "Fila {$rowNumber}: no se encontró el código de la asignatura en «{$asignaturaLabel}».",
                $rowNumber,
                $cens,
            );
            return;
        }

        $matched = 0;
        foreach ($cursos as $curso) {
            $codigosCurso = array_values(array_filter(array_map(
                static fn (string $part): string => trim($part),
                explode(',', (string) ($curso['asignatura_codigo'] ?? '')),
            ), static fn (string $part): bool => $part !== ''));

            if (!in_array($codigo, $codigosCurso, true)) {
                continue;
            }
            $matched++;

            $cursoId = (string) $curso['curso_id'];
            $asignaturaNombre = (string) ($curso['asignatura_nombre'] ?? '');
            $tomaId = trim((string) ($curso['toma_activa_id'] ?? ''));
            $tomaDocenteId = trim((string) ($curso['toma_docente_id'] ?? ''));

            if ($tomaId === '') {
                $this->insertToma($cursoId, $personaId);
                $report['tomas_creadas']++;
                $this->log(
                    $report,
                    'success',
                    "Fila {$rowNumber}: toma creada para curso {$cursoId} ({$asignaturaNombre}).",
                    $rowNumber,
                    $cens,
                );
            } elseif ($tomaDocenteId === $personaId) {
                $report['tomas_existentes_mismo']++;
                $this->log(
                    $report,
                    'info',
                    "Fila {$rowNumber}: toma ya existe para curso {$cursoId} ({$asignaturaNombre}).",
                    $rowNumber,
                    $cens,
                );
            } else {
                $report['tomas_existentes_otro']++;
                $existente = PersonaName::label([
                    'nombres' => $curso['toma_docente_nombres'] ?? '',
                    'apellidos' => $curso['toma_docente_apellidos'] ?? '',
                    'numero_documento' => $curso['toma_docente_documento'] ?? '',
                ]);
                $this->log(
                    $report,
                    'conflict',
                    "Fila {$rowNumber}: VERIFICAR toma de {$asignaturaNombre} (curso {$cursoId}). "
                        . "Ya está {$existente}. La planilla indica otro docente. No se modificó.",
                    $rowNumber,
                    $cens,
                );
            }
        }

        if ($matched === 0) {
            $this->log(
                $report,
                'warning',
                "Fila {$rowNumber}: ningún curso de la comisión coincide con el código de asignatura «{$codigo}».",
                $rowNumber,
                $cens,
            );
        }
    }

    private function insertToma(string $cursoId, string $personaId): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO toma (id, fecha_toma, curso, docente, estado, tipo_movimiento, estado_contralor)
            VALUES (:id, :fecha_toma, :curso, :docente, :estado, :tipo_movimiento, :estado_contralor)
        ");
        $stmt->execute([
            'id' => uniqid(),
            'fecha_toma' => (new \DateTimeImmutable('today'))->format('Y-m-d'),
            'curso' => $cursoId,
            'docente' => $personaId,
            'estado' => 'Pendiente',
            'tipo_movimiento' => 'AI',
            'estado_contralor' => 'Pasar',
        ]);
    }

    private function comisionIdByPfidAndCalendario(string $pfid, string $calendarioId): ?string
    {
        $stmt = $this->pdo->prepare("
            SELECT id
            FROM comision
            WHERE pfid = :pfid
              AND calendario = :calendario
            LIMIT 1
        ");
        $stmt->execute([
            'pfid' => $pfid,
            'calendario' => $calendarioId,
        ]);
        $id = $stmt->fetchColumn();

        return $id === false || $id === null || $id === '' ? null : (string) $id;
    }

    /**
     * Cursos de la comisión con eventual toma activa (Aprobada/Pendiente + contralor Pasar).
     *
     * @return list<array{
     *   curso_id: string,
     *   asignatura_codigo: ?string,
     *   asignatura_nombre: ?string,
     *   toma_activa_id: ?string,
     *   toma_docente_id: ?string,
     *   toma_docente_nombres: ?string,
     *   toma_docente_apellidos: ?string,
     *   toma_docente_documento: ?string
     * }>
     */
    private function cursosConTomaActivaByComision(string $comisionId): array
    {
        $cursosStmt = $this->pdo->prepare("
            SELECT curso.id AS curso_id,
                   asignatura.codigo AS asignatura_codigo,
                   asignatura.nombre AS asignatura_nombre
            FROM curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            WHERE curso.comision = :comision
            ORDER BY asignatura.nombre ASC, curso.id ASC
        ");
        $cursosStmt->execute(['comision' => $comisionId]);
        $cursos = $cursosStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        if ($cursos === []) {
            return [];
        }

        $cursoIds = array_map(static fn (array $c): string => (string) $c['curso_id'], $cursos);
        $placeholders = implode(', ', array_map(static fn (int $i): string => ":c{$i}", array_keys($cursoIds)));
        $params = [];
        foreach ($cursoIds as $i => $id) {
            $params["c{$i}"] = $id;
        }

        $tomasStmt = $this->pdo->prepare("
            SELECT toma.id, toma.curso, toma.docente, toma.fecha_toma, toma.alta,
                   persona.nombres AS docente_nombres,
                   persona.apellidos AS docente_apellidos,
                   persona.numero_documento AS docente_documento
            FROM toma
            LEFT JOIN persona ON persona.id = toma.docente
            WHERE toma.curso IN ({$placeholders})
              AND (toma.estado = 'Aprobada' OR toma.estado = 'Pendiente')
              AND toma.estado_contralor = 'Pasar'
            ORDER BY toma.fecha_toma DESC, toma.alta DESC, toma.id DESC
        ");
        $tomasStmt->execute($params);

        $tomaByCurso = [];
        while ($toma = $tomasStmt->fetch(PDO::FETCH_ASSOC)) {
            $cursoKey = (string) $toma['curso'];
            if (!isset($tomaByCurso[$cursoKey])) {
                $tomaByCurso[$cursoKey] = $toma;
            }
        }

        foreach ($cursos as &$curso) {
            $cursoKey = (string) $curso['curso_id'];
            $toma = $tomaByCurso[$cursoKey] ?? null;
            $curso['toma_activa_id'] = $toma['id'] ?? null;
            $curso['toma_docente_id'] = $toma['docente'] ?? null;
            $curso['toma_docente_nombres'] = $toma['docente_nombres'] ?? null;
            $curso['toma_docente_apellidos'] = $toma['docente_apellidos'] ?? null;
            $curso['toma_docente_documento'] = $toma['docente_documento'] ?? null;
        }
        unset($curso);

        return $cursos;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findPersonaByDni(string $dni): ?array
    {
        $candidates = array_values(array_unique([
            $dni,
            str_pad($dni, 8, '0', STR_PAD_LEFT),
            ltrim($dni, '0') !== '' ? ltrim($dni, '0') : $dni,
        ]));
        $placeholders = implode(', ', array_map(static fn (int $i): string => ":d{$i}", array_keys($candidates)));
        $params = [];
        foreach ($candidates as $i => $value) {
            $params["d{$i}"] = $value;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, nombres, apellidos, numero_documento, descripcion_domicilio, localidad,
                   telefono, email, email_abc, nacionalidad, fecha_nacimiento,
                   dia_nacimiento, mes_nacimiento, anio_nacimiento, genero, sexo
            FROM persona
            WHERE numero_documento IN ({$placeholders})
            LIMIT 1
        ");
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function personaPayload(array $row, string $dni): array
    {
        $nombres = trim((string) ($row['nombres'] ?? ''));
        $apellidos = trim((string) ($row['apellidos'] ?? ''));
        $fecha = $this->parseDate(isset($row['fecha_nacimiento']) ? (string) $row['fecha_nacimiento'] : null);

        $payload = [
            'nombres' => $nombres !== '' ? $nombres : null,
            'apellidos' => $apellidos !== '' ? $apellidos : null,
            'numero_documento' => $dni,
            'descripcion_domicilio' => $this->nullableText($row['descripcion_domicilio'] ?? null),
            'localidad' => $this->nullableText($row['localidad'] ?? null),
            'telefono' => $this->nullableText($row['telefono'] ?? null),
            'email' => $this->nullableText($row['email'] ?? null),
            'email_abc' => $this->nullableText($row['email_abc'] ?? null),
            'nacionalidad' => 'Argentina',
            'fecha_nacimiento' => $fecha,
            'dia_nacimiento' => null,
            'mes_nacimiento' => null,
            'anio_nacimiento' => null,
            'genero' => null,
            'sexo' => null,
        ];

        if ($fecha !== null) {
            $dt = \DateTimeImmutable::createFromFormat('Y-m-d', $fecha);
            if ($dt instanceof \DateTimeImmutable) {
                $payload['dia_nacimiento'] = (int) $dt->format('d');
                $payload['mes_nacimiento'] = (int) $dt->format('m');
                $payload['anio_nacimiento'] = (int) $dt->format('Y');
            }
        }

        $generoRaw = $this->nullableText($row['genero'] ?? null);
        if ($generoRaw !== null) {
            $inicial = mb_strtolower(mb_substr($generoRaw, 0, 1));
            if ($inicial === 'm') {
                $payload['genero'] = 'Masculino';
                $payload['sexo'] = 1;
            } else {
                $payload['genero'] = 'Femenino';
                $payload['sexo'] = 2;
            }
        }

        return $payload;
    }

    /**
     * @param array<string, mixed> $existing
     * @param array<string, mixed> $incoming
     * @return array{changed: bool, diffs: list<string>, data: array<string, mixed>}
     */
    private function mergePersonaFields(array $existing, array $incoming): array
    {
        $labels = [
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'descripcion_domicilio' => 'domicilio',
            'localidad' => 'localidad',
            'telefono' => 'teléfono',
            'email' => 'email',
            'email_abc' => 'email ABC',
            'nacionalidad' => 'nacionalidad',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'dia_nacimiento' => 'día de nacimiento',
            'mes_nacimiento' => 'mes de nacimiento',
            'anio_nacimiento' => 'año de nacimiento',
            'genero' => 'género',
            'sexo' => 'sexo',
        ];

        $data = [];
        $changed = false;
        $diffs = [];
        foreach (array_keys($labels) as $field) {
            $newValue = $incoming[$field] ?? null;
            $oldValue = $existing[$field] ?? null;

            if ($this->isEmptyValue($newValue)) {
                $data[$field] = $oldValue;
                continue;
            }

            if ($this->isEmptyValue($oldValue)) {
                $data[$field] = $newValue;
                $changed = true;
                continue;
            }

            if ($this->personaValuesDiffer($field, $oldValue, $newValue)) {
                $data[$field] = $oldValue;
                $diffs[] = $labels[$field] . ' «' . $this->displayValue($oldValue) . '» (BD) vs «'
                    . $this->displayValue($newValue) . '» (planilla)';
            } else {
                $data[$field] = $oldValue;
            }
        }

        if ($this->isEmptyValue($data['nacionalidad'] ?? null)) {
            $data['nacionalidad'] = 'Argentina';
            if ($this->isEmptyValue($existing['nacionalidad'] ?? null)) {
                $changed = true;
            }
        }

        if ($this->isEmptyValue($data['nombres'] ?? null)) {
            $data['nombres'] = (string) ($existing['nombres'] ?? '');
        }

        return ['changed' => $changed, 'diffs' => $diffs, 'data' => $data];
    }

    private function personaValuesDiffer(string $field, mixed $oldValue, mixed $newValue): bool
    {
        if (in_array($field, ['email', 'email_abc'], true)) {
            return mb_strtolower(trim((string) $oldValue)) !== mb_strtolower(trim((string) $newValue));
        }
        if ($field === 'telefono') {
            $oldDigits = preg_replace('/\D+/', '', (string) $oldValue) ?? '';
            $newDigits = preg_replace('/\D+/', '', (string) $newValue) ?? '';

            return $oldDigits !== $newDigits;
        }
        if (in_array($field, ['nombres', 'apellidos', 'descripcion_domicilio', 'localidad', 'nacionalidad', 'genero'], true)) {
            $normalize = static fn (mixed $value): string => (string) preg_replace(
                '/\s+/u',
                ' ',
                mb_strtolower(trim((string) $value)),
            );

            return $normalize($oldValue) !== $normalize($newValue);
        }

        return (string) $oldValue !== (string) $newValue;
    }

    private function displayValue(mixed $value): string
    {
        $text = trim((string) $value);

        return $text === '' ? '(vacío)' : $text;
    }

    private function parseDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $raw = trim($value);

        if (preg_match('/^\d{4,5}(\.0+)?$/', $raw) === 1) {
            $serial = (int) $raw;
            if ($serial > 20000 && $serial < 80000) {
                $base = new \DateTimeImmutable('1899-12-30');
                return $base->modify("+{$serial} days")->format('Y-m-d');
            }
        }

        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'd/m/y', 'Y/m/d', 'm/d/Y'];
        foreach ($formats as $format) {
            $dt = \DateTimeImmutable::createFromFormat('!' . $format, $raw);
            if ($dt instanceof \DateTimeImmutable) {
                $errors = \DateTimeImmutable::getLastErrors();
                $hasErrors = is_array($errors)
                    && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0);
                if (!$hasErrors) {
                    return $dt->format('Y-m-d');
                }
            }
        }

        $ts = strtotime($raw);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function isEmptyValue(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private function assertCalendarioExists(string $id): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM calendario WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        if ($stmt->fetchColumn() === false) {
            throw new \RuntimeException('El calendario indicado no existe.');
        }
    }

    /**
     * @param array<string, mixed> $report
     */
    private function log(
        array &$report,
        string $level,
        string $message,
        ?int $row = null,
        ?string $cens = null,
    ): void {
        $entry = [
            'level' => $level,
            'message' => $message,
        ];
        if ($row !== null) {
            $entry['row'] = $row;
        }
        if ($cens !== null && $cens !== '') {
            $entry['cens'] = $cens;
        }
        $report['log'][] = $entry;
    }
}
