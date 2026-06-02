<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class AlumnoComisionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byAlumno(string $alumnoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT alumno_comision.id,
                   alumno_comision.comision AS comision_id,
                   alumno_comision.estado,
                   alumno_comision.activo,
                   comision.pfid,
                   COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion
            FROM alumno_comision
            LEFT JOIN comision ON comision.id = alumno_comision.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE alumno_comision.alumno = :alumno_id
            ORDER BY alumno_comision.id DESC
        ");
        $stmt->execute(['alumno_id' => $alumnoId]);

        return $stmt->fetchAll();
    }

    public function estados(): array
    {
        return $this->pdo
            ->query("SELECT DISTINCT estado FROM alumno_comision WHERE estado IS NOT NULL AND estado != '' ORDER BY estado ASC")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public function search(string $term, int $limit = 10): array
    {
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        $limit = max(1, min(20, $limit));
        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.pfid,
                   COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE comision.id LIKE :term_like OR comision.pfid LIKE :term_like
            ORDER BY (comision.pfid = :term_exact_pfid) DESC,
                     (comision.id = :term_exact_id) DESC,
                     CAST(comision.pfid AS UNSIGNED) DESC,
                     comision.pfid DESC
            LIMIT {$limit}
        ");
        $stmt->execute([
            'term_like' => '%' . $term . '%',
            'term_exact_pfid' => $term,
            'term_exact_id' => $term,
        ]);

        return array_map(static function (array $row): array {
            $plan = trim(implode(' - ', array_filter([$row['plan_orientacion'] ?? '', $row['plan_resolucion'] ?? ''])));
            $summary = trim(implode(' | ', array_filter([
                'PFID ' . ($row['pfid'] ?? ''),
                $row['sede_label'] ?? '',
                $row['calendario_label'] ?? '',
                $row['tramo_label'] ?? '',
                $plan,
            ])));
            return [
                'id' => (string) ($row['id'] ?? ''),
                'pfid' => (string) ($row['pfid'] ?? ''),
                'label' => $summary,
            ];
        }, $stmt->fetchAll());
    }

    public function saveForAlumno(string $alumnoId, array $rows): void
    {
        foreach ($rows as $row) {
            $comisionId = $this->resolveComisionRef((string) ($row['comision_ref'] ?? ''));
            if ($comisionId === null) {
                continue;
            }

            if (!empty($row['id'])) {
                $stmt = $this->pdo->prepare("
                    UPDATE alumno_comision
                    SET comision = :comision, estado = :estado, activo = :activo
                    WHERE id = :id AND alumno = :alumno
                ");
                $stmt->execute([
                    'id' => $row['id'],
                    'alumno' => $alumnoId,
                    'comision' => $comisionId,
                    'estado' => $row['estado'] ?: null,
                    'activo' => (int) ($row['activo'] ?? 0),
                ]);
                continue;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO alumno_comision (id, alumno, comision, estado, activo)
                VALUES (:id, :alumno, :comision, :estado, :activo)
            ");
            $stmt->execute([
                'id' => uniqid(),
                'alumno' => $alumnoId,
                'comision' => $comisionId,
                'estado' => $row['estado'] ?: null,
                'activo' => (int) ($row['activo'] ?? 0),
            ]);
        }
    }

    private function resolveComisionRef(string $ref): ?string
    {
        $ref = trim($ref);
        if ($ref === '') {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT id FROM comision WHERE id = :ref LIMIT 1');
        $stmt->execute(['ref' => $ref]);
        $id = $stmt->fetchColumn();

        if ($id !== false) {
            return (string) $id;
        }

        throw new \RuntimeException('La comision seleccionada no existe. Elegi una opcion del autocompletar.');
    }
}
