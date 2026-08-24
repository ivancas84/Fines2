<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use FinesApp\Support\ComisionesPciParser;
use FinesApp\Support\PersonaName;
use PDO;

/**
 * Importación del Informe Global por Comisión PCI.
 *
 * Actualiza horarios de cursos AREA A–E y crea tomas pendientes
 * cuando el docente (por DNI) existe y el curso no tiene toma Aprobada/Pendiente.
 */
final class ComisionesPciImportRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{
     *   lines_total: int,
     *   comisiones_detectadas: int,
     *   areas_detectadas: int,
     *   cursos_procesados: int,
     *   horarios_actualizados: int,
     *   tomas_creadas: int,
     *   tomas_ok: int,
     *   tomas_conflicto: int,
     *   personas_diferentes: int,
     *   docentes_sin_designar: int,
     *   docentes_inexistentes: int,
     *   cursos_no_encontrados: int,
     *   comisiones_omitidas: int,
     *   errores: int,
     *   log: list<array{level: string, message: string}>
     * }
     */
    public function process(string $rawData, string $calendarioId): array
    {
        $calendarioId = trim($calendarioId);
        if ($calendarioId === '') {
            throw new \InvalidArgumentException('Seleccioná un calendario.');
        }
        $this->assertCalendarioExists($calendarioId);

        $pfids = $this->pfidsByCalendario($calendarioId);
        $pfidSet = array_fill_keys($pfids, true);

        $parsed = ComisionesPciParser::parse($rawData);
        $pfidsDetectados = [];
        foreach ($parsed['rows'] as $row) {
            $pfidsDetectados[$row['pfid']] = true;
        }

        $report = [
            'lines_total' => $parsed['lines_kept'],
            'comisiones_detectadas' => count($pfidsDetectados),
            'areas_detectadas' => count($parsed['rows']),
            'cursos_procesados' => 0,
            'horarios_actualizados' => 0,
            'tomas_creadas' => 0,
            'tomas_ok' => 0,
            'tomas_conflicto' => 0,
            'personas_diferentes' => 0,
            'docentes_sin_designar' => 0,
            'docentes_inexistentes' => 0,
            'cursos_no_encontrados' => 0,
            'comisiones_omitidas' => 0,
            'errores' => 0,
            'log' => [],
        ];

        foreach ($parsed['warnings'] as $warning) {
            $this->log($report, 'warning', $warning);
        }

        if ($parsed['rows'] === []) {
            $this->log($report, 'warning', 'No se detectaron comisiones ni áreas en el texto pegado.');
            return $report;
        }

        $this->log(
            $report,
            'info',
            sprintf(
                'Detectadas %d comisiones y %d áreas (líneas útiles: %d).',
                $report['comisiones_detectadas'],
                $report['areas_detectadas'],
                $report['lines_total'],
            ),
        );

        $lastPfid = '';
        $omittedPfids = [];

        foreach ($parsed['rows'] as $row) {
            $pfid = $row['pfid'];
            $area = $row['area'];
            $context = "{$pfid} Área {$area}";

            if ($pfid !== $lastPfid) {
                $lastPfid = $pfid;
                $tramo = $row['tramo'] !== null ? " ({$row['tramo']})" : '';
                $this->log($report, 'info', "Comisión {$pfid}{$tramo}");
            }

            if (!isset($pfidSet[$pfid])) {
                $report['comisiones_omitidas']++;
                if (!isset($omittedPfids[$pfid])) {
                    $omittedPfids[$pfid] = true;
                    $this->log(
                        $report,
                        'warning',
                        "Comisión {$pfid} no pertenece al calendario seleccionado (se omiten sus áreas).",
                    );
                }
                continue;
            }

            $cursoId = $this->cursoIdByArea($pfid, $area, $calendarioId);
            if ($cursoId === null) {
                $report['cursos_no_encontrados']++;
                $this->log($report, 'warning', "No existe curso {$context}");
                continue;
            }

            $report['cursos_procesados']++;
            $this->updateHorario($cursoId, $row['horario']);
            $report['horarios_actualizados']++;
            $this->log($report, 'success', "{$context}: horario «{$row['horario']}»");

            if ($row['sin_designar']) {
                $report['docentes_sin_designar']++;
                $this->log($report, 'warning', "{$context}: docente sin designar");
                continue;
            }

            if ($row['dni'] === null) {
                $report['errores']++;
                $this->log($report, 'error', "{$context}: no se pudo leer el DNI del docente");
                continue;
            }

            $persona = $this->findPersonaByDni($row['dni']);
            if ($persona === null) {
                $report['errores']++;
                $report['docentes_inexistentes']++;
                $nombre = $row['docente_nombre'] !== null ? " ({$row['docente_nombre']})" : '';
                $this->log(
                    $report,
                    'error',
                    "{$context}: no existe docente DNI {$row['dni']}{$nombre}. Cargalo en Personas y reintentá.",
                );
                continue;
            }

            $nombreInforme = trim((string) ($row['docente_nombre'] ?? ''));
            if ($nombreInforme !== '' && !PersonaName::nombreParecido($persona, [
                'nombres' => $nombreInforme,
                'apellidos' => '',
            ])) {
                $report['personas_diferentes']++;
                $this->log(
                    $report,
                    'conflict',
                    "{$context}: VERIFICAR persona DNI {$row['dni']}: «"
                        . PersonaName::label($persona) . "» (BD) vs «{$nombreInforme}» (informe). No se actualizó.",
                );
            }

            $toma = $this->tomaAprobadaOPendiente($cursoId);
            if ($toma === null) {
                try {
                    $this->insertToma($cursoId, (string) $persona['id']);
                    $report['tomas_creadas']++;
                    $this->log($report, 'success', "{$context}: toma agregada para DNI {$row['dni']}");
                } catch (\Throwable $throwable) {
                    $report['errores']++;
                    $this->log($report, 'error', "{$context}: error al agregar toma: " . $throwable->getMessage());
                }
                continue;
            }

            if ((string) ($toma['docente'] ?? '') !== (string) $persona['id']) {
                $report['tomas_conflicto']++;
                $existente = PersonaName::label([
                    'nombres' => $toma['nombres'] ?? '',
                    'apellidos' => $toma['apellidos'] ?? '',
                    'numero_documento' => $toma['numero_documento'] ?? '',
                ]);
                $entrante = PersonaName::label($persona);
                $this->log(
                    $report,
                    'conflict',
                    "{$context}: VERIFICAR toma. Ya está {$existente}. El informe indica {$entrante}. No se modificó.",
                );
                continue;
            }

            $report['tomas_ok']++;
            $this->log($report, 'info', "{$context}: toma existente con el mismo docente");
        }

        return $report;
    }

    private function cursoIdByArea(string $pfid, string $area, string $calendarioId): ?string
    {
        $codigo = 'AREA ' . strtoupper($area);
        $codigoNorm = 'AREA' . strtoupper($area);

        $stmt = $this->pdo->prepare("
            SELECT curso.id
            FROM curso
            INNER JOIN disposicion ON disposicion.id = curso.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN comision ON comision.id = curso.comision
            WHERE comision.pfid = :pfid
              AND comision.calendario = :calendario
              AND (
                    UPPER(TRIM(asignatura.codigo)) = :codigo
                 OR REPLACE(UPPER(TRIM(asignatura.codigo)), ' ', '') = :codigo_norm
                 OR UPPER(TRIM(asignatura.nombre)) = :codigo
              )
            LIMIT 1
        ");
        $stmt->execute([
            'pfid' => $pfid,
            'calendario' => $calendarioId,
            'codigo' => $codigo,
            'codigo_norm' => $codigoNorm,
        ]);
        $id = $stmt->fetchColumn();

        return $id === false || $id === null || $id === '' ? null : (string) $id;
    }

    private function updateHorario(string $cursoId, string $descripcionHorario): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE curso
            SET descripcion_horario = :horario
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $cursoId,
            'horario' => $descripcionHorario,
        ]);
    }

    /**
     * @return array{id: string, docente: ?string, nombres?: ?string, apellidos?: ?string, numero_documento?: ?string}|null
     */
    private function tomaAprobadaOPendiente(string $cursoId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id, toma.docente,
                   persona.nombres, persona.apellidos, persona.numero_documento
            FROM toma
            LEFT JOIN persona ON persona.id = toma.docente
            WHERE toma.curso = :curso
              AND (toma.estado = 'Aprobada' OR toma.estado = 'Pendiente')
            ORDER BY toma.fecha_toma DESC, toma.alta DESC, toma.id DESC
            LIMIT 1
        ");
        $stmt->execute(['curso' => $cursoId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
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

    /**
     * @return array{id: string, numero_documento: string, nombres?: ?string, apellidos?: ?string}|null
     */
    private function findPersonaByDni(string $dni): ?array
    {
        $dni = preg_replace('/\D+/', '', $dni) ?? '';
        if ($dni === '') {
            return null;
        }

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
            SELECT id, numero_documento, nombres, apellidos
            FROM persona
            WHERE numero_documento IN ({$placeholders})
            LIMIT 1
        ");
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    /**
     * @return list<string>
     */
    private function pfidsByCalendario(string $calendarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT pfid
            FROM comision
            WHERE calendario = :calendario
              AND pfid IS NOT NULL
              AND TRIM(pfid) <> ''
        ");
        $stmt->execute(['calendario' => $calendarioId]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        return array_values(array_map(static fn ($v): string => trim((string) $v), $rows));
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
    private function log(array &$report, string $level, string $message): void
    {
        $report['log'][] = [
            'level' => $level,
            'message' => $message,
        ];
    }
}
