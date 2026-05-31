<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class CalificacionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byAlumno(string $alumnoId): array
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
            WHERE calificacion.alumno = :alumno_id
            ORDER BY CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     asignatura.nombre ASC
        ");
        $stmt->execute(['alumno_id' => $alumnoId]);

        return $stmt->fetchAll();
    }
}
