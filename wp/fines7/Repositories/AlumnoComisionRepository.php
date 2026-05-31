<?php

namespace Fines7\Repositories;

use PDO;

class AlumnoComisionRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byAlumno(string $alumnoId): array
    {
        $sql = "
            SELECT
                alumno_comision.id,
                alumno_comision.comision AS comision_id,
                alumno_comision.estado,
                alumno_comision.activo,
                alumno_comision.observaciones,
                comision.pfid,
                COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(calendario.anio, ''),
                    NULLIF(calendario.semestre, '')
                )) AS calendario_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(planificacion.anio, ''),
                    NULLIF(planificacion.semestre, '')
                )) AS tramo_label,
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
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['alumno_id' => $alumnoId]);

        return $stmt->fetchAll();
    }

    public function estados(): array
    {
        $sql = "
            SELECT DISTINCT estado
            FROM alumno_comision
            WHERE estado IS NOT NULL
              AND estado != ''
            ORDER BY estado ASC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function searchComisiones(string $term, int $limit = 10): array
    {
        $limit = max(1, min(20, $limit));
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        $sql = "
            SELECT
                comision.id,
                comision.pfid,
                COALESCE(sede.nombre, sede.numero, 'Sede no definida') AS sede_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(calendario.anio, ''),
                    NULLIF(calendario.semestre, '')
                )) AS calendario_label,
                TRIM(CONCAT_WS('-',
                    NULLIF(planificacion.anio, ''),
                    NULLIF(planificacion.semestre, '')
                )) AS tramo_label,
                plan.orientacion AS plan_orientacion,
                plan.resolucion AS plan_resolucion
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE comision.id LIKE :term_like
               OR comision.pfid LIKE :term_like
            ORDER BY
                (comision.pfid = :term_exact_pfid) DESC,
                (comision.id = :term_exact_id) DESC,
                CAST(comision.pfid AS UNSIGNED) DESC,
                comision.pfid DESC
            LIMIT {$limit}
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'term_like' => '%' . $term . '%',
            'term_exact_pfid' => $term,
            'term_exact_id' => $term,
        ]);

        return array_map(function (array $row): array {
            $planLabel = trim(implode(' - ', array_filter([
                (string) ($row['plan_orientacion'] ?? ''),
                (string) ($row['plan_resolucion'] ?? ''),
            ])));

            $summary = trim(implode(' | ', array_filter([
                'PFID ' . (string) ($row['pfid'] ?? ''),
                (string) ($row['sede_label'] ?? ''),
            ])));

            $label = trim(implode(' | ', array_filter([
                $summary,
                (string) ($row['calendario_label'] ?? ''),
                (string) ($row['tramo_label'] ?? ''),
                $planLabel,
                'ID ' . (string) ($row['id'] ?? ''),
            ])));

            return [
                'id' => (string) ($row['id'] ?? ''),
                'pfid' => (string) ($row['pfid'] ?? ''),
                'summary' => $summary,
                'label' => $label,
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
                $this->update((string) $row['id'], $alumnoId, $comisionId, $row);
            } else {
                $this->insert($alumnoId, $comisionId, $row);
            }
        }
    }

    private function update(string $id, string $alumnoId, string $comisionId, array $row): void
    {
        $sql = "
            UPDATE alumno_comision
            SET comision = :comision,
                estado = :estado,
                activo = :activo
            WHERE id = :id
              AND alumno = :alumno
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'alumno' => $alumnoId,
            'comision' => $comisionId,
            'estado' => $row['estado'] ?? null,
            'activo' => (int) ($row['activo'] ?? 0),
        ]);
    }

    private function insert(string $alumnoId, string $comisionId, array $row): void
    {
        $sql = "
            INSERT INTO alumno_comision (id, alumno, comision, estado, activo)
            VALUES (:id, :alumno, :comision, :estado, :activo)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => uniqid(),
            'alumno' => $alumnoId,
            'comision' => $comisionId,
            'estado' => $row['estado'] ?? null,
            'activo' => (int) ($row['activo'] ?? 0),
        ]);
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

        throw new \RuntimeException('La comision seleccionada no existe. Elegi una opcion del autocompletar para guardar el ID.');
    }
}
