<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class DocenteCalificacionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function completadasByDocente(string $personaId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT calificacion.id,
                   calificacion.nota1,
                   calificacion.nota2,
                   calificacion.nota3,
                   calificacion.nota_final,
                   calificacion.crec,
                   calificacion.fecha,
                   calificacion.observaciones,
                   curso.id AS curso_id,
                   comision.pfid,
                   asignatura.nombre AS asignatura_label,
                   TRIM(CONCAT_WS('-', NULLIF(planificacion.anio, ''), NULLIF(planificacion.semestre, ''))) AS tramo_label,
                   TRIM(CONCAT_WS(' ', persona_alumno.apellidos, persona_alumno.nombres)) AS alumno_label,
                   persona_alumno.numero_documento AS alumno_documento
            FROM calificacion
            INNER JOIN curso ON curso.id = calificacion.curso
            INNER JOIN toma ON toma.curso = curso.id
            INNER JOIN alumno ON alumno.id = calificacion.alumno
            INNER JOIN persona persona_alumno ON persona_alumno.id = alumno.persona
            INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion
            LEFT JOIN comision ON comision.id = curso.comision
            WHERE toma.docente = :persona_id
              AND (
                  calificacion.nota1 > 0
                  OR calificacion.nota2 > 0
                  OR calificacion.nota3 > 0
                  OR calificacion.nota_final > 0
                  OR calificacion.crec > 0
              )
              AND toma.estado IN ('Aprobada', 'Pendiente')
              AND (toma.estado_contralor IS NULL OR toma.estado_contralor != 'Modificar')
            ORDER BY calificacion.fecha DESC,
                     persona_alumno.apellidos ASC,
                     persona_alumno.nombres ASC,
                     asignatura.nombre ASC
        ");
        $stmt->execute(['persona_id' => $personaId]);

        return $stmt->fetchAll();
    }
}
