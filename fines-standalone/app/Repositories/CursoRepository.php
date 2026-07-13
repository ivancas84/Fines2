<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class CursoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function calendarios(): array
    {
        return $this->pdo
            ->query('SELECT id, anio, semestre, descripcion FROM calendario ORDER BY anio DESC, semestre DESC')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function porCalendario(string $calendarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT curso.id AS curso_id,
                   curso.comision AS comision_id,
                   curso.horas_catedra AS curso_horas_catedra,
                   curso.descripcion_horario,
                   comision.pfid,
                   comision.sede AS sede_id,
                   COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   asignatura.nombre AS asignatura_nombre,
                   asignatura.codigo AS asignatura_codigo,
                   disposicion.id AS disposicion_id,
                   disposicion.horas_catedra AS disposicion_horas_catedra,
                   toma_activa.id AS toma_id,
                   toma_activa.fecha_toma,
                   toma_activa.estado AS toma_estado,
                   toma_activa.estado_contralor,
                   toma_activa.estado_planilla,
                   planilla_docente.numero AS planilla_numero,
                   docente.id AS docente_id,
                    TRIM(CONCAT_WS(' ', NULLIF(docente.apellidos, ''), NULLIF(docente.nombres, ''))) AS docente_nombre,
                    docente.nombres AS docente_nombres,
                   docente.email AS docente_email,
                   docente.email_abc AS docente_email_abc,
                   docente.telefono AS docente_telefono,
                   COALESCE(aprobados.cantidad, 0) AS cantidad_aprobados
            FROM curso
            INNER JOIN comision ON comision.id = curso.comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN disposicion ON disposicion.id = curso.disposicion
            LEFT JOIN asignatura ON asignatura.id = disposicion.asignatura
            LEFT JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN (
                SELECT toma.curso, MIN(toma.id) AS toma_id
                FROM toma
                INNER JOIN curso curso_toma ON curso_toma.id = toma.curso
                INNER JOIN comision comision_toma ON comision_toma.id = curso_toma.comision
                WHERE comision_toma.calendario = :calendario_tomas
                  AND toma.estado = 'Aprobada'
                  AND toma.estado_contralor != 'Modificar'
                GROUP BY toma.curso
            ) toma_por_curso ON toma_por_curso.curso = curso.id
            LEFT JOIN toma toma_activa ON toma_activa.id = toma_por_curso.toma_id
            LEFT JOIN persona docente ON docente.id = toma_activa.docente
            LEFT JOIN planilla_docente ON planilla_docente.id = toma_activa.planilla_docente
            LEFT JOIN (
                SELECT curso_aprobados.id AS curso_id,
                       COUNT(DISTINCT calificacion.alumno) AS cantidad
                FROM curso curso_aprobados
                INNER JOIN comision comision_aprobados ON comision_aprobados.id = curso_aprobados.comision
                INNER JOIN alumno_comision ON alumno_comision.comision = comision_aprobados.id
                INNER JOIN calificacion ON calificacion.alumno = alumno_comision.alumno
                    AND calificacion.disposicion = curso_aprobados.disposicion
                    AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                WHERE comision_aprobados.calendario = :calendario_aprobados
                GROUP BY curso_aprobados.id
            ) aprobados ON aprobados.curso_id = curso.id
            WHERE comision.calendario = :calendario_cursos
              AND comision.autorizada = 1
            ORDER BY COALESCE(sede.nombre, sede.numero, '') ASC,
                     CAST(comision.pfid AS UNSIGNED) ASC,
                     comision.pfid ASC,
                     CAST(planificacion.anio AS UNSIGNED) ASC,
                     CAST(planificacion.semestre AS UNSIGNED) ASC,
                     asignatura.nombre ASC
        ");
        $stmt->execute([
            'calendario_tomas' => $calendarioId,
            'calendario_aprobados' => $calendarioId,
            'calendario_cursos' => $calendarioId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
