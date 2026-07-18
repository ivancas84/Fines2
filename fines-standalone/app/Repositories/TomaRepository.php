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

    public function byComision(string $comisionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.fecha_toma,
                   toma.estado,
                   toma.tipo_movimiento,
                   toma.estado_contralor,
                   toma.estado_planilla,
                   toma.observaciones,
                   toma.curso AS curso_id,
                   toma.docente AS docente_id,
                   curso.descripcion_horario,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   docente.nombres AS docente_nombres,
                   docente.apellidos AS docente_apellidos,
                   docente.numero_documento AS docente_documento,
                   docente.telefono AS docente_telefono,
                   docente.email AS docente_email,
                   docente.email_abc AS docente_email_abc,
                   planilla_docente.numero AS planilla_numero
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN persona docente ON docente.id = toma.docente
            LEFT JOIN planilla_docente ON planilla_docente.id = toma.planilla_docente
            WHERE curso.comision = :comision_id
            ORDER BY toma.fecha_toma DESC, toma.alta DESC, toma.id DESC
        ");
        $stmt->execute(['comision_id' => $comisionId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param list<array{
     *   id: string,
     *   fecha_toma?: mixed,
     *   curso?: mixed,
     *   dni_docente?: mixed,
     *   estado?: mixed,
     *   tipo_movimiento?: mixed,
     *   estado_contralor?: mixed
     * }> $rows
     */
    public function updateForComision(string $comisionId, array $rows): void
    {
        $this->pdo->beginTransaction();
        try {
            foreach ($rows as $row) {
                $id = trim((string) ($row['id'] ?? ''));
                if ($id === '') {
                    continue;
                }

                $current = $this->findInComision($comisionId, $id);
                if ($current === null) {
                    throw new \RuntimeException("No se encontró la toma {$id} en la comisión.");
                }

                $docenteId = (string) ($current['docente'] ?? '');
                $dniNuevo = preg_replace('/\D+/', '', (string) ($row['dni_docente'] ?? '')) ?? '';
                $dniActual = preg_replace('/\D+/', '', (string) ($current['docente_documento'] ?? '')) ?? '';
                if ($dniNuevo !== '' && $dniNuevo !== $dniActual) {
                    $docenteId = $this->personaIdByDni($dniNuevo);
                }

                $tipoMovimiento = $this->nullableText($row['tipo_movimiento'] ?? null)
                    ?? $this->nullableText($current['tipo_movimiento'] ?? null)
                    ?? 'Alta';

                $stmt = $this->pdo->prepare("
                    UPDATE toma
                    SET fecha_toma = :fecha_toma,
                        curso = :curso,
                        docente = :docente,
                        estado = :estado,
                        tipo_movimiento = :tipo_movimiento,
                        estado_contralor = :estado_contralor
                    WHERE id = :id
                ");
                $stmt->execute([
                    'id' => $id,
                    'fecha_toma' => $this->nullableDate($row['fecha_toma'] ?? null),
                    'curso' => $this->cursoInComision($comisionId, (string) ($row['curso'] ?? $current['curso'])),
                    'docente' => $docenteId !== '' ? $docenteId : null,
                    'estado' => $this->nullableText($row['estado'] ?? null),
                    'tipo_movimiento' => $tipoMovimiento,
                    'estado_contralor' => $this->nullableText($row['estado_contralor'] ?? null),
                ]);
            }

            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }
    }

    public function addForComision(string $comisionId, array $data): string
    {
        $cursoId = $this->cursoInComision($comisionId, (string) ($data['curso'] ?? ''));
        $dni = preg_replace('/\D+/', '', (string) ($data['dni_docente'] ?? '')) ?? '';
        if ($dni === '') {
            throw new \InvalidArgumentException('Indicá el DNI del docente.');
        }
        $docenteId = $this->personaIdByDni($dni);
        $tipoMovimiento = $this->nullableText($data['tipo_movimiento'] ?? null);
        if ($tipoMovimiento === null) {
            throw new \InvalidArgumentException('Indicá el tipo de movimiento.');
        }

        $id = uniqid();
        $stmt = $this->pdo->prepare("
            INSERT INTO toma (id, fecha_toma, curso, docente, estado, tipo_movimiento, estado_contralor)
            VALUES (:id, :fecha_toma, :curso, :docente, :estado, :tipo_movimiento, :estado_contralor)
        ");
        $stmt->execute([
            'id' => $id,
            'fecha_toma' => $this->nullableDate($data['fecha_toma'] ?? null),
            'curso' => $cursoId,
            'docente' => $docenteId,
            'estado' => $this->nullableText($data['estado'] ?? null),
            'tipo_movimiento' => $tipoMovimiento,
            'estado_contralor' => $this->nullableText($data['estado_contralor'] ?? null),
        ]);

        return $id;
    }

    public function deleteInComision(string $comisionId, string $tomaId): void
    {
        $current = $this->findInComision($comisionId, $tomaId);
        if ($current === null) {
            throw new \RuntimeException('No se encontró la toma para eliminar.');
        }

        $stmt = $this->pdo->prepare('DELETE FROM toma WHERE id = :id');
        $stmt->execute(['id' => $tomaId]);
    }

    /** @return array<string, mixed>|null */
    private function findInComision(string $comisionId, string $tomaId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT toma.id,
                   toma.curso,
                   toma.docente,
                   toma.tipo_movimiento,
                   persona.numero_documento AS docente_documento
            FROM toma
            INNER JOIN curso ON curso.id = toma.curso
            LEFT JOIN persona ON persona.id = toma.docente
            WHERE toma.id = :toma_id
              AND curso.comision = :comision_id
            LIMIT 1
        ");
        $stmt->execute([
            'toma_id' => $tomaId,
            'comision_id' => $comisionId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    private function cursoInComision(string $comisionId, string $cursoId): string
    {
        $cursoId = trim($cursoId);
        if ($cursoId === '') {
            throw new \InvalidArgumentException('Seleccioná un curso.');
        }

        $stmt = $this->pdo->prepare('
            SELECT id FROM curso
            WHERE id = :curso_id AND comision = :comision_id
            LIMIT 1
        ');
        $stmt->execute([
            'curso_id' => $cursoId,
            'comision_id' => $comisionId,
        ]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            throw new \RuntimeException('El curso no pertenece a la comisión.');
        }

        return (string) $id;
    }

    private function personaIdByDni(string $dni): string
    {
        $stmt = $this->pdo->prepare('SELECT id FROM persona WHERE numero_documento = :dni LIMIT 1');
        $stmt->execute(['dni' => $dni]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            throw new \RuntimeException('No se encontró el docente con el DNI proporcionado.');
        }

        return (string) $id;
    }

    public function updateEstadoPlanilla(string $tomaId, string $estado = 'entregada'): void
    {
        $tomaId = trim($tomaId);
        $estado = trim($estado);
        if ($tomaId === '') {
            throw new \InvalidArgumentException('Falta el id de la toma.');
        }
        if ($estado === '') {
            throw new \InvalidArgumentException('Falta el estado de planilla.');
        }

        $stmt = $this->pdo->prepare('
            UPDATE toma
            SET estado_planilla = :estado
            WHERE id = :id
        ');
        $stmt->execute([
            'id' => $tomaId,
            'estado' => $estado,
        ]);

        if ($stmt->rowCount() === 0) {
            $exists = $this->pdo->prepare('SELECT id FROM toma WHERE id = :id LIMIT 1');
            $exists->execute(['id' => $tomaId]);
            if ($exists->fetchColumn() === false) {
                throw new \RuntimeException('No se encontró la toma indicada.');
            }
            // rowCount 0 puede ser "mismo valor"; no es error.
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
