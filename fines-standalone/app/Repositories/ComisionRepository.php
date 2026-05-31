<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class ComisionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function calendarios(): array
    {
        return $this->pdo
            ->query('SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC')
            ->fetchAll();
    }

    public function comisiones(string $calendarioId, bool $soloAutorizadas, string $sort = 'pfid', string $order = 'asc'): array
    {
        $autorizadaSql = $soloAutorizadas ? 'AND comision.autorizada = 1' : '';
        $orderBy = $this->orderBy($sort, $order);

        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.sede AS sede_id,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS(' ', NULLIF(domicilio.calle, ''), NULLIF(domicilio.entre, ''), NULLIF(domicilio.numero, ''), NULLIF(domicilio.barrio, ''), NULLIF(domicilio.localidad, ''))) AS domicilio_label,
                   comision.pfid,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS planificacion_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion,
                   comision.autorizada,
                   comision.apertura,
                   comision.turno,
                   COALESCE(alumnos.cantidad_alumnos, 0) AS cantidad_alumnos,
                   COALESCE(alumnos.cantidad_alumnos_activos, 0) AS cantidad_alumnos_activos,
                   comision_siguiente.pfid AS comision_siguiente_pfid,
                   planificacion_siguiente.anio AS comision_siguiente_anio,
                   planificacion_siguiente.semestre AS comision_siguiente_semestre,
                   COALESCE(referentes.referentes_label, 'Sin Referentes') AS referentes_label
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            LEFT JOIN comision comision_siguiente ON comision_siguiente.id = comision.comision_siguiente
            LEFT JOIN planificacion planificacion_siguiente ON planificacion_siguiente.id = comision_siguiente.planificacion
            LEFT JOIN (
                SELECT alumno_comision.comision,
                       COUNT(alumno_comision.id) AS cantidad_alumnos,
                       SUM(alumno_comision.activo = 1) AS cantidad_alumnos_activos
                FROM alumno_comision
                INNER JOIN comision c2 ON c2.id = alumno_comision.comision
                WHERE c2.calendario = :calendario_alumnos
                GROUP BY alumno_comision.comision
            ) alumnos ON alumnos.comision = comision.id
            LEFT JOIN (
                SELECT designacion.sede,
                       GROUP_CONCAT(CONCAT_WS(' ', NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), ''), NULLIF(TRIM(persona.telefono), '')) ORDER BY persona.apellidos, persona.nombres SEPARATOR ', ') AS referentes_label
                FROM designacion
                INNER JOIN persona ON persona.id = designacion.persona
                WHERE designacion.cargo = '1' AND designacion.hasta IS NULL
                GROUP BY designacion.sede
            ) referentes ON referentes.sede = comision.sede
            WHERE comision.calendario = :calendario_comision
            {$autorizadaSql}
            ORDER BY {$orderBy}
        ");
        $stmt->execute([
            'calendario_alumnos' => $calendarioId,
            'calendario_comision' => $calendarioId,
        ]);

        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['plan_label'] = trim(implode(' ', array_filter([
                $this->acronym((string) ($row['plan_orientacion'] ?? '')),
                (string) ($row['plan_resolucion'] ?? ''),
            ])));
        }

        return $rows;
    }

    private function acronym(string $value): string
    {
        $words = preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return implode('', array_map(static fn (string $word): string => substr($word, 0, 1), $words));
    }

    private function orderBy(string $sort, string $order): string
    {
        $direction = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
        $sorts = [
            'nombre' => "sede_nombre {$direction}, comision.pfid ASC",
            'pfid' => "CAST(comision.pfid AS UNSIGNED) {$direction}, comision.pfid {$direction}",
            'planificacion' => "CAST(planificacion.anio AS UNSIGNED) {$direction}, CAST(planificacion.semestre AS UNSIGNED) {$direction}, plan.orientacion {$direction}, plan.resolucion {$direction}, comision.pfid ASC",
            'apertura' => "comision.apertura {$direction}, comision.pfid ASC",
            'turno' => "comision.turno {$direction}, comision.pfid ASC",
        ];

        return $sorts[$sort] ?? $sorts['pfid'];
    }
}
