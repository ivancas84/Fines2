<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class TomaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byDocente(string $personaId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.fecha_toma,
                   toma.estado,
                   toma.tipo_movimiento,
                   toma.estado_contralor,
                   toma.estado_planilla,
                   toma.reclamo,
                   toma.observaciones,
                   toma.curso AS curso_id,
                   curso.descripcion_horario,
                   curso.codigo AS curso_codigo,
                   asignatura.nombre AS asignatura_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   comision.id AS comision_id,
                   comision.pfid,
                   COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   calendario.inicio AS calendario_inicio,
                   calendario.fin AS calendario_fin,
                   planilla_docente.numero AS planilla_numero
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN comision ON comision.id = curso.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planilla_docente ON planilla_docente.id = toma.planilla_docente
            WHERE toma.docente = :persona_id
            ORDER BY toma.fecha_toma DESC,
                     toma.alta DESC,
                     toma.id DESC
        ");
        $stmt->execute(['persona_id' => $personaId]);

        return $stmt->fetchAll();
    }

    public function estados(): array
    {
        return $this->distinctColumn('estado');
    }

    public function tiposMovimiento(): array
    {
        return $this->distinctColumn('tipo_movimiento');
    }

    public function estadosContralor(): array
    {
        return $this->distinctColumn('estado_contralor');
    }

    public function updateForDocente(string $personaId, array $rows): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE toma
            SET fecha_toma = :fecha_toma,
                estado = :estado,
                tipo_movimiento = :tipo_movimiento,
                estado_contralor = :estado_contralor,
                observaciones = :observaciones
            WHERE id = :id AND docente = :docente
        ");

        foreach ($rows as $row) {
            $id = (string) ($row['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $stmt->execute([
                'id' => $id,
                'docente' => $personaId,
                'fecha_toma' => $this->nullableDate($row['fecha_toma'] ?? null),
                'estado' => $this->nullableText($row['estado'] ?? null),
                'tipo_movimiento' => $this->nullableText($row['tipo_movimiento'] ?? null),
                'estado_contralor' => $this->nullableText($row['estado_contralor'] ?? null),
                'observaciones' => $this->nullableText($row['observaciones'] ?? null),
            ]);
        }
    }

    private function distinctColumn(string $column): array
    {
        $allowed = ['estado', 'tipo_movimiento', 'estado_contralor'];
        if (!in_array($column, $allowed, true)) {
            throw new \InvalidArgumentException('Columna de toma no permitida.');
        }

        return $this->pdo
            ->query("SELECT DISTINCT {$column} FROM toma WHERE {$column} IS NOT NULL AND {$column} != '' ORDER BY {$column} ASC")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    private function nullableDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches) === 1) {
            $day = (int) $matches[1];
            $month = (int) $matches[2];
            $year = (int) $matches[3];
            if (checkdate($month, $day, $year)) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }

        throw new \RuntimeException('La fecha de toma debe tener formato dd/mm/aaaa.');
    }
}
