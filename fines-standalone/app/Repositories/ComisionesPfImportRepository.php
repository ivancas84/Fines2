<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use FinesApp\Support\PersonaName;
use PDO;

/**
 * Importación del informe global de comisiones PF
 * (migrado de wp/pfpc2_procesar_comisiones_pf).
 *
 * Formato esperado (texto copiado del informe):
 * - Línea de curso/horario con un día de la semana, p. ej.:
 *   `{pfid}/{codigo} ... Lunes 18:00 a 20:00`
 * - Línea(s) siguientes con docente (CUIL `XX-XXXXXXXX-X`) o `*` si no hay designado.
 */
final class ComisionesPfImportRepository
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

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{
     *   lines_total: int,
     *   cursos_procesados: int,
     *   horarios_actualizados: int,
     *   cuils_actualizados: int,
     *   personas_diferentes: int,
     *   tomas_creadas: int,
     *   tomas_ok: int,
     *   tomas_conflicto: int,
     *   docentes_sin_designar: int,
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

        $report = [
            'lines_total' => 0,
            'cursos_procesados' => 0,
            'horarios_actualizados' => 0,
            'cuils_actualizados' => 0,
            'personas_diferentes' => 0,
            'tomas_creadas' => 0,
            'tomas_ok' => 0,
            'tomas_conflicto' => 0,
            'docentes_sin_designar' => 0,
            'cursos_no_encontrados' => 0,
            'comisiones_omitidas' => 0,
            'errores' => 0,
            'log' => [],
        ];

        $lines = preg_split("/\r\n|\n|\r/", $rawData) ?: [];
        $lines = array_values(array_filter(
            array_map(static fn (string $line): string => rtrim($line), $lines),
            static fn (string $line): bool => trim($line) !== '',
        ));
        $report['lines_total'] = count($lines);

        $procesarDocente = false;
        $comisionPfid = '';
        $asignaturaCodigo = '';
        $cursoId = '';

        foreach ($lines as $line) {
            if ($procesarDocente) {
                $this->processDocenteLine(
                    $report,
                    $line,
                    $comisionPfid,
                    $asignaturaCodigo,
                    $cursoId,
                    $procesarDocente,
                );

                // Si todavía esperamos docente (línea intermedia), no interpretar como curso.
                if ($procesarDocente) {
                    continue;
                }
            }

            $diaEncontrado = $this->findDiaInLine($line);
            if ($diaEncontrado === null) {
                continue;
            }

            $parsed = $this->parseCursoLine($line, $diaEncontrado);
            if ($parsed === null) {
                $report['errores']++;
                $this->log($report, 'error', "No se pudo interpretar la línea de curso: {$line}");
                continue;
            }

            $comisionPfid = $parsed['pfid'];
            $asignaturaCodigo = $parsed['codigo'];
            $descripcionHorario = $parsed['horario'];

            if (!isset($pfidSet[$comisionPfid])) {
                $report['comisiones_omitidas']++;
                continue;
            }

            $this->log($report, 'info', "Procesando comisión {$comisionPfid}");
            $this->log($report, 'info', "Línea: {$line}");

            $cursoIdFound = $this->cursoIdByParams($comisionPfid, $asignaturaCodigo, $calendarioId);
            if ($cursoIdFound === null) {
                $report['cursos_no_encontrados']++;
                $this->log(
                    $report,
                    'warning',
                    "No existe curso {$comisionPfid} {$asignaturaCodigo}",
                );
                $procesarDocente = false;
                $cursoId = '';
                continue;
            }

            $cursoId = $cursoIdFound;
            $report['cursos_procesados']++;
            $this->log(
                $report,
                'success',
                "Curso existente {$comisionPfid} {$asignaturaCodigo} ({$cursoId})",
            );

            $this->updateHorario($cursoId, $descripcionHorario);
            $report['horarios_actualizados']++;
            $this->log($report, 'success', "Horario actualizado: {$descripcionHorario}");
            $procesarDocente = true;
        }

        return $report;
    }

    /**
     * @param array<string, mixed> $report
     */
    private function processDocenteLine(
        array &$report,
        string $line,
        string $comisionPfid,
        string $asignaturaCodigo,
        string $cursoId,
        bool &$procesarDocente,
    ): void {
        $context = trim($comisionPfid . ' ' . $asignaturaCodigo);

        if (str_contains($line, '*')) {
            $report['docentes_sin_designar']++;
            $this->log($report, 'warning', "Docente sin designar en curso {$context}");
            $procesarDocente = false;
            return;
        }

        // Líneas intermedias sin guión (no son CUIL): se saltan y se sigue esperando docente.
        if (!str_contains($line, '-')) {
            $this->log($report, 'info', "Salto de línea en curso {$context}");
            return;
        }

        $procesarDocente = false;
        $line = str_replace('--', '-', $line); // CUIL mal escrito con doble guion

        if (preg_match('/\d{2}-\d{8}-\d/', $line, $matches) !== 1) {
            $report['errores']++;
            $this->log($report, 'warning', "No hay match de CUIL en: {$line}");
            return;
        }

        $cuil = $matches[0];
        $cuilParts = explode('-', $cuil);
        if (count($cuilParts) !== 3) {
            $report['errores']++;
            $this->log($report, 'error', "CUIL inválido: {$cuil}");
            return;
        }

        $dni = $cuilParts[1];
        $persona = $this->findPersonaByDni($dni);
        if ($persona === null) {
            $report['errores']++;
            $this->log($report, 'error', "No existe docente {$cuil}");
            return;
        }

        $nombreInforme = trim(str_replace([$cuil, '--'], ['', '-'], $line));
        $nombreInforme = trim((string) preg_replace('/\s+/u', ' ', $nombreInforme));
        if ($nombreInforme !== '' && !PersonaName::nombreParecido($persona, [
            'nombres' => $nombreInforme,
            'apellidos' => '',
        ])) {
            $report['personas_diferentes']++;
            $this->log(
                $report,
                'conflict',
                "VERIFICAR persona DNI {$dni}: nombre «" . PersonaName::label($persona)
                    . "» (BD) vs «{$nombreInforme}» (informe). No se actualizó.",
            );
        }

        $cuilSinGuiones = implode('', $cuilParts);
        $storedCuil = preg_replace('/\D+/', '', (string) ($persona['cuil'] ?? '')) ?? '';
        if ($storedCuil === '') {
            $this->updatePersonaCuil(
                (string) $persona['id'],
                $cuilSinGuiones,
                (int) $cuilParts[0],
                (int) $cuilParts[2],
            );
            $report['cuils_actualizados']++;
            $this->log($report, 'success', "CUIL completado {$cuilSinGuiones}");
        } elseif ($storedCuil !== $cuilSinGuiones) {
            $report['personas_diferentes']++;
            $this->log(
                $report,
                'conflict',
                "VERIFICAR persona DNI {$dni}: CUIL «{$storedCuil}» (BD) vs «{$cuilSinGuiones}» (informe). No se actualizó.",
            );
        }

        if ($cursoId === '') {
            $report['errores']++;
            $this->log($report, 'error', "Sin curso activo para asociar toma del docente {$cuil}");
            return;
        }

        $toma = $this->tomaAprobadaOPendiente($cursoId);
        if ($toma === null) {
            try {
                $this->insertToma($cursoId, (string) $persona['id']);
                $report['tomas_creadas']++;
                $this->log($report, 'success', "Toma agregada para curso {$cursoId}");
            } catch (\Throwable $throwable) {
                $report['errores']++;
                $this->log($report, 'error', 'Error al agregar toma: ' . $throwable->getMessage());
            }
            return;
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
                "VERIFICAR toma del curso {$context} ({$cursoId}): ya está {$existente}. "
                    . "El informe indica {$entrante}. No se modificó.",
            );
            return;
        }

        $report['tomas_ok']++;
        $this->log($report, 'info', "Toma existente con el mismo docente (curso {$cursoId})");
    }

    private function findDiaInLine(string $line): ?string
    {
        foreach (self::DIAS as $dia) {
            if (str_contains($line, $dia)) {
                return $dia;
            }
        }

        return null;
    }

    /**
     * @return array{pfid: string, codigo: string, horario: string}|null
     */
    private function parseCursoLine(string $line, string $dia): ?array
    {
        $slashPos = strpos($line, '/');
        if ($slashPos === false) {
            return null;
        }

        $pfid = trim(substr($line, 0, $slashPos));
        if ($pfid === '') {
            return null;
        }

        $afterSlash = substr($line, $slashPos + 1);
        $spacePos = strpos($afterSlash, ' ');
        $codigoRaw = $spacePos === false
            ? trim($afterSlash)
            : trim(substr($afterSlash, 0, $spacePos));

        if ($codigoRaw === '') {
            return null;
        }

        // Igual que el script original: recorta a 5 caracteres si viene más largo.
        $codigo = mb_strlen($codigoRaw) > 5 ? mb_substr($codigoRaw, 0, 5) : $codigoRaw;

        $diaPos = strpos($line, $dia);
        if ($diaPos === false) {
            return null;
        }
        $horario = trim(substr($line, $diaPos));
        if ($horario === '') {
            return null;
        }

        return [
            'pfid' => $pfid,
            'codigo' => $codigo,
            'horario' => $horario,
        ];
    }

    private function cursoIdByParams(string $pfid, string $codigo, string $calendarioId): ?string
    {
        $stmt = $this->pdo->prepare("
            SELECT curso.id
            FROM curso
            INNER JOIN disposicion ON disposicion.id = curso.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN comision ON comision.id = curso.comision
            WHERE comision.pfid = :pfid
              AND comision.calendario = :calendario
              AND asignatura.codigo LIKE :codigo
            LIMIT 1
        ");
        $stmt->execute([
            'pfid' => $pfid,
            'calendario' => $calendarioId,
            'codigo' => '%' . $codigo . '%',
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
     * @return array{id: string, numero_documento: string, nombres?: ?string, apellidos?: ?string, cuil?: ?string}|null
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
            SELECT id, numero_documento, nombres, apellidos, cuil
            FROM persona
            WHERE numero_documento IN ({$placeholders})
            LIMIT 1
        ");
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    private function updatePersonaCuil(string $personaId, string $cuil, int $cuil1, int $cuil2): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE persona
            SET cuil = :cuil,
                cuil1 = :cuil1,
                cuil2 = :cuil2
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $personaId,
            'cuil' => $cuil,
            'cuil1' => $cuil1,
            'cuil2' => $cuil2,
        ]);
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
