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
}
