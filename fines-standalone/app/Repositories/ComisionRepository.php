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

    public function byId(string $comisionId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT comision.id,
                   comision.pfid,
                   comision.turno,
                   comision.autorizada,
                   comision.apertura,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS('-', NULLIF(calendario.anio, ''), NULLIF(calendario.semestre, ''))) AS calendario_label,
                   calendario.descripcion AS calendario_descripcion,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS planificacion_label,
                   plan.orientacion AS plan_orientacion,
                   plan.resolucion AS plan_resolucion
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN calendario ON calendario.id = comision.calendario
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN plan ON plan.id = planificacion.plan
            WHERE comision.id = :comision_id
            LIMIT 1
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function alumnos(string $comisionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT alumno_comision.id AS alumno_comision_id,
                   alumno_comision.activo,
                   alumno_comision.estado,
                   alumno.id AS alumno_id,
                   alumno.persona AS persona_id,
                   alumno.plan AS alumno_plan_id,
                   alumno.anio_ingreso,
                   alumno.semestre_ingreso,
                   persona.apellidos,
                   persona.nombres,
                   persona.cuil,
                   persona.cuil1,
                   persona.numero_documento,
                   persona.cuil2,
                   persona.codigo_area,
                   persona.telefono,
                   persona.email,
                   persona.fecha_nacimiento,
                   persona.sexo
            FROM alumno_comision
            INNER JOIN alumno ON alumno.id = alumno_comision.alumno
            INNER JOIN persona ON persona.id = alumno.persona
            WHERE alumno_comision.comision = :comision_id
            ORDER BY alumno_comision.activo DESC,
                     persona.apellidos ASC,
                     persona.nombres ASC
        ");
        $stmt->execute(['comision_id' => $comisionId]);
        $alumnos = $stmt->fetchAll();

        if ($alumnos === []) {
            return [];
        }

        $aprobadasStmt = $this->pdo->prepare("
            SELECT calificacion.alumno AS alumno_id,
                   planificacion.plan AS plan_id,
                   planificacion.anio,
                   planificacion.semestre,
                   COUNT(DISTINCT calificacion.id) AS cantidad
            FROM alumno_comision
            INNER JOIN calificacion ON calificacion.alumno = alumno_comision.alumno
            INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            WHERE alumno_comision.comision = :comision_id
              AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
            GROUP BY calificacion.alumno,
                     planificacion.plan,
                     planificacion.anio,
                     planificacion.semestre
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC
        ");
        $aprobadasStmt->execute(['comision_id' => $comisionId]);

        $aprobadasPorAlumno = [];
        foreach ($aprobadasStmt->fetchAll() as $aprobada) {
            $aprobadasPorAlumno[(string) $aprobada['alumno_id']][] = $aprobada;
        }

        foreach ($alumnos as &$alumno) {
            $tramoIngreso = $this->tramoIngresoShort($alumno);
            $planId = (string) ($alumno['alumno_plan_id'] ?? '');
            $porTramo = [];
            $otrosPlanes = 0;

            foreach ($aprobadasPorAlumno[(string) $alumno['alumno_id']] ?? [] as $aprobada) {
                $tramoCalificacion = (string) $aprobada['anio'] . (string) $aprobada['semestre'];
                if ($tramoCalificacion < $tramoIngreso) {
                    continue;
                }

                $cantidad = (int) $aprobada['cantidad'];
                if ($planId !== '' && (string) $aprobada['plan_id'] === $planId) {
                    $porTramo[] = [
                        'label' => $aprobada['anio'] . '° ' . $aprobada['semestre'] . 'C',
                        'cantidad' => $cantidad,
                    ];
                } elseif ($planId !== '') {
                    $otrosPlanes += $cantidad;
                }
            }

            $alumno['aprobadas_por_tramo'] = $porTramo;
            $alumno['aprobadas_otros_planes'] = $otrosPlanes;
        }
        unset($alumno);

        return $alumnos;
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

    private function tramoIngresoShort(array $alumno): string
    {
        $anio = trim((string) ($alumno['anio_ingreso'] ?? ''));
        if ($anio === '') {
            return '11';
        }

        $semestre = trim((string) ($alumno['semestre_ingreso'] ?? ''));

        return $anio . ($semestre !== '' ? $semestre : '1');
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
