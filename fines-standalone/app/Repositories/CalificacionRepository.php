<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class CalificacionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byAlumnoPlanTramo(string $alumnoId, string $planId, string $tramoIngreso): array
    {
        return $this->fetchByAlumno("
            calificacion.alumno = :alumno_id
            AND plan.id = :plan_id
            AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_ingreso
        ", [
            'alumno_id' => $alumnoId,
            'plan_id' => $planId,
            'tramo_ingreso' => $tramoIngreso,
        ]);
    }

    public function aprobadasByAlumnoNotInPlan(string $alumnoId, string $planId): array
    {
        return $this->fetchByAlumno("
            calificacion.alumno = :alumno_id
            AND plan.id != :plan_id
            AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
        ", [
            'alumno_id' => $alumnoId,
            'plan_id' => $planId,
        ]);
    }

    public function sincronizarByAlumno(array $alumno): array
    {
        $alumnoId = (string) ($alumno['id'] ?? '');
        $planId = (string) ($alumno['plan'] ?? '');
        if ($alumnoId === '' || $planId === '') {
            throw new \InvalidArgumentException('El alumno debe tener un plan para sincronizar calificaciones.');
        }

        $tramoIngreso = $this->tramoIngresoShort($alumno);

        $this->pdo->beginTransaction();
        try {
            $deleteStmt = $this->pdo->prepare("
                DELETE FROM calificacion
                WHERE alumno = :alumno_id
                  AND (nota_final < 7 OR nota_final IS NULL)
                  AND (crec < 4 OR crec IS NULL)
            ");
            $deleteStmt->execute(['alumno_id' => $alumnoId]);
            $deleted = $deleteStmt->rowCount();

            $disposicionesStmt = $this->pdo->prepare("
                SELECT disposicion.id
                FROM disposicion
                INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
                WHERE planificacion.plan = :plan_id
                  AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_ingreso
                ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                         CAST(planificacion.semestre AS UNSIGNED) ASC,
                         disposicion.orden_informe_coordinacion_distrital ASC,
                         disposicion.id ASC
            ");
            $disposicionesStmt->execute([
                'plan_id' => $planId,
                'tramo_ingreso' => $tramoIngreso,
            ]);
            $disposicionIds = array_map('strval', $disposicionesStmt->fetchAll(PDO::FETCH_COLUMN));

            $aprobadasStmt = $this->pdo->prepare("
                SELECT DISTINCT calificacion.disposicion
                FROM calificacion
                INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
                INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
                WHERE calificacion.alumno = :alumno_id
                  AND planificacion.plan = :plan_id
                  AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_ingreso
                  AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
            ");
            $aprobadasStmt->execute([
                'alumno_id' => $alumnoId,
                'plan_id' => $planId,
                'tramo_ingreso' => $tramoIngreso,
            ]);
            $aprobadas = array_flip(array_map('strval', $aprobadasStmt->fetchAll(PDO::FETCH_COLUMN)));

            $insertStmt = $this->pdo->prepare("
                INSERT INTO calificacion (id, alumno, disposicion, archivado, nota_final, crec)
                VALUES (:id, :alumno_id, :disposicion_id, 0, 0, 0)
            ");

            $inserted = 0;
            foreach ($disposicionIds as $disposicionId) {
                if (isset($aprobadas[$disposicionId])) {
                    continue;
                }

                $insertStmt->execute([
                    'id' => uniqid(),
                    'alumno_id' => $alumnoId,
                    'disposicion_id' => $disposicionId,
                ]);
                $inserted++;
            }

            $this->pdo->commit();

            return [
                'deleted' => $deleted,
                'inserted' => $inserted,
            ];
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    public function updateEditableFields(string $alumnoId, array $rows): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE calificacion
            SET nota_final = :nota_final,
                crec = :crec,
                curso = :curso,
                observaciones = :observaciones
            WHERE id = :id AND alumno = :alumno_id
        ");

        foreach ($rows as $row) {
            $id = (string) ($row['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $stmt->execute([
                'id' => $id,
                'alumno_id' => $alumnoId,
                'nota_final' => $this->nullableDecimal($row['nota_final'] ?? null),
                'crec' => $this->nullableDecimal($row['crec'] ?? null),
                'curso' => $this->validCursoId($alumnoId, $id, $row['curso'] ?? null),
                'observaciones' => $this->nullableText($row['observaciones'] ?? null),
            ]);
        }
    }

    public function searchCursos(string $term, string $disposicionId, int $limit = 10): array
    {
        $term = trim($term);
        $disposicionId = trim($disposicionId);
        if ($term === '' || $disposicionId === '') {
            return [];
        }

        $limit = max(1, min(20, $limit));
        $stmt = $this->pdo->prepare("
            SELECT curso.id,
                   comision.pfid,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   asignatura.nombre AS asignatura_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   COALESCE(toma_activa.docente_label, '') AS docente_label
            FROM curso
            INNER JOIN comision ON comision.id = curso.comision
            INNER JOIN disposicion ON disposicion.id = curso.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN (
                SELECT toma.curso,
                       GROUP_CONCAT(NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), '') ORDER BY persona.apellidos, persona.nombres SEPARATOR ', ') AS docente_label
                FROM toma
                LEFT JOIN persona ON persona.id = toma.docente
                WHERE toma.estado = 'Aprobada' AND toma.estado_contralor = 'Pasar'
                GROUP BY toma.curso
            ) toma_activa ON toma_activa.curso = curso.id
            WHERE curso.disposicion = :disposicion_id
              AND (curso.id LIKE :term_like OR comision.pfid LIKE :term_like)
            ORDER BY (curso.id = :term_exact_id) DESC,
                     (comision.pfid = :term_exact_pfid) DESC,
                     CAST(comision.pfid AS UNSIGNED) DESC,
                     comision.pfid DESC,
                     curso.id DESC
            LIMIT {$limit}
        ");
        $stmt->execute([
            'disposicion_id' => $disposicionId,
            'term_like' => '%' . $term . '%',
            'term_exact_id' => $term,
            'term_exact_pfid' => $term,
        ]);

        return array_map(static function (array $row): array {
            $label = trim(implode(' | ', array_filter([
                ($row['pfid'] ?? '') !== '' ? 'PFID ' . $row['pfid'] : '',
                $row['calendario_label'] ?? '',
                $row['docente_label'] ?? '',
                trim(implode(' ', array_filter([
                    $row['asignatura_label'] ?? '',
                    $row['tramo_label'] ?? '',
                ]))),
            ])));

            return [
                'id' => (string) ($row['id'] ?? ''),
                'pfid' => (string) ($row['pfid'] ?? ''),
                'label' => $label,
            ];
        }, $stmt->fetchAll());
    }

    public function tramoIngresoShort(array $alumno): string
    {
        $anioIngreso = (string) ($alumno['anio_ingreso'] ?? '');
        if ($anioIngreso === '') {
            return '11';
        }

        $semestreIngreso = (string) ($alumno['semestre_ingreso'] ?? '');

        return $anioIngreso . ($semestreIngreso !== '' ? $semestreIngreso : '1');
    }

    private function fetchByAlumno(string $whereSql, array $params): array
    {
        $stmt = $this->pdo->prepare("
            SELECT calificacion.id,
                   asignatura.nombre AS asignatura_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   calificacion.nota_final,
                   calificacion.crec,
                   calificacion.observaciones,
                   calificacion.curso,
                   calificacion.disposicion,
                   comision.pfid,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   COALESCE(toma_activa.docente_label, '') AS docente_label
            FROM calificacion
            INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            INNER JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN curso ON curso.id = calificacion.curso
            LEFT JOIN comision ON comision.id = curso.comision
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN (
                SELECT toma.curso,
                       GROUP_CONCAT(NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), '') ORDER BY persona.apellidos, persona.nombres SEPARATOR ', ') AS docente_label
                FROM toma
                LEFT JOIN persona ON persona.id = toma.docente
                WHERE toma.estado = 'Aprobada' AND toma.estado_contralor = 'Pasar'
                GROUP BY toma.curso
            ) toma_activa ON toma_activa.curso = curso.id
            WHERE {$whereSql}
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     asignatura.nombre ASC
        ");
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    private function nullableDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) str_replace(',', '.', (string) $value), 2, '.', '');
    }

    private function nullableText(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    private function validCursoId(string $alumnoId, string $calificacionId, mixed $cursoId): ?string
    {
        if ($cursoId === null || $cursoId === '') {
            return null;
        }

        $cursoId = (string) $cursoId;
        $stmt = $this->pdo->prepare("
            SELECT curso.id
            FROM calificacion
            INNER JOIN curso ON curso.id = :curso_id AND curso.disposicion = calificacion.disposicion
            WHERE calificacion.id = :calificacion_id AND calificacion.alumno = :alumno_id
            LIMIT 1
        ");
        $stmt->execute([
            'curso_id' => $cursoId,
            'calificacion_id' => $calificacionId,
            'alumno_id' => $alumnoId,
        ]);
        $validCursoId = $stmt->fetchColumn();

        if ($validCursoId === false) {
            throw new \RuntimeException('El curso seleccionado no corresponde a la disposicion de la calificacion.');
        }

        return (string) $validCursoId;
    }
}
