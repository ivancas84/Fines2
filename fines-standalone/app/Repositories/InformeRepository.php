<?php

declare(strict_types=1);

namespace FinesApp\Repositories;

use PDO;

final class InformeRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function egresadosPorCalendario(): array
    {
        $stmt = $this->pdo->query("
            SELECT calendario.anio,
                   calendario.semestre,
                   COUNT(DISTINCT alumnos_tramo_32.alumno) AS cantidad_egresados
            FROM (
                SELECT alumno_comision.alumno,
                       comision.calendario
                FROM alumno_comision
                INNER JOIN comision ON comision.id = alumno_comision.comision
                INNER JOIN planificacion comision_planificacion ON comision_planificacion.id = comision.planificacion
                WHERE comision_planificacion.anio = 3
                  AND comision_planificacion.semestre = 2
            ) alumnos_tramo_32
            INNER JOIN calendario ON calendario.id = alumnos_tramo_32.calendario
            INNER JOIN (
                SELECT calificacion.alumno
                FROM calificacion
                INNER JOIN disposicion ON disposicion.id = calificacion.disposicion
                INNER JOIN planificacion disposicion_planificacion ON disposicion_planificacion.id = disposicion.planificacion
                WHERE disposicion_planificacion.anio = 3
                  AND disposicion_planificacion.semestre = 2
                  AND (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                GROUP BY calificacion.alumno
                HAVING COUNT(DISTINCT calificacion.disposicion) >= 5
            ) egresados ON egresados.alumno = alumnos_tramo_32.alumno
            GROUP BY calendario.anio, calendario.semestre
            ORDER BY calendario.anio DESC,
                     calendario.semestre DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
