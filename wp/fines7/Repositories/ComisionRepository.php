<?php

namespace Fines7\Repositories;

use PDO;

class ComisionRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function calendarios(): array
    {
        $sql = "
            SELECT id, anio, semestre, descripcion
            FROM calendario
            ORDER BY anio DESC, semestre DESC
        ";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function comisiones(string $calendarioId, bool $soloAutorizadas): array
    {
        $params = [
            'calendario_alumnos' => $calendarioId,
            'calendario_comision' => $calendarioId,
        ];
        $autorizadaSql = '';

        if ($soloAutorizadas) {
            $autorizadaSql = 'AND comision.autorizada = 1';
        }

        $sql = "
            SELECT
                comision.id,
                comision.sede AS sede_id,
                COALESCE(sede.nombre, sede.numero, '?') AS sede_nombre,
                TRIM(CONCAT_WS(' ',
                    NULLIF(domicilio.calle, ''),
                    NULLIF(domicilio.entre, ''),
                    NULLIF(domicilio.numero, ''),
                    NULLIF(domicilio.barrio, ''),
                    NULLIF(domicilio.localidad, '')
                )) AS domicilio_label,
                comision.pfid,
                TRIM(CONCAT_WS('-',
                    NULLIF(planificacion.anio, ''),
                    NULLIF(planificacion.semestre, '')
                )) AS planificacion_label,
                planificacion.plan AS plan_label,
                comision.autorizada,
                comision.apertura,
                comision.turno,
                COALESCE(alumnos.cantidad_alumnos, 0) AS cantidad_alumnos,
                COALESCE(alumnos.cantidad_alumnos_activos, 0) AS cantidad_alumnos_activos,
                comision.comision_siguiente,
                comision_siguiente.pfid AS comision_siguiente_pfid,
                planificacion_siguiente.anio AS comision_siguiente_anio,
                planificacion_siguiente.semestre AS comision_siguiente_semestre,
                COALESCE(referentes.referentes_label, 'Sin Referentes') AS referentes_label
            FROM comision
            LEFT JOIN sede ON sede.id = comision.sede
            LEFT JOIN domicilio ON domicilio.id = sede.domicilio
            LEFT JOIN planificacion ON planificacion.id = comision.planificacion
            LEFT JOIN comision comision_siguiente ON comision_siguiente.id = comision.comision_siguiente
            LEFT JOIN planificacion planificacion_siguiente ON planificacion_siguiente.id = comision_siguiente.planificacion
            LEFT JOIN (
                SELECT
                    alumno_comision.comision,
                    COUNT(alumno_comision.id) AS cantidad_alumnos,
                    SUM(alumno_comision.activo = 1) AS cantidad_alumnos_activos
                FROM alumno_comision
                INNER JOIN comision c2 ON c2.id = alumno_comision.comision
                WHERE c2.calendario = :calendario_alumnos
                GROUP BY alumno_comision.comision
            ) alumnos ON alumnos.comision = comision.id
            LEFT JOIN (
                SELECT
                    designacion.sede,
                    GROUP_CONCAT(
                        CONCAT_WS(' ',
                            NULLIF(TRIM(CONCAT_WS(' ', persona.apellidos, persona.nombres)), ''),
                            NULLIF(TRIM(persona.telefono), '')
                        )
                        ORDER BY persona.apellidos, persona.nombres
                        SEPARATOR ', '
                    ) AS referentes_label
                FROM designacion
                INNER JOIN persona ON persona.id = designacion.persona
                WHERE designacion.cargo = '1' /* referente */
                  AND designacion.hasta IS NULL
                GROUP BY designacion.sede
            ) referentes ON referentes.sede = comision.sede
            WHERE comision.calendario = :calendario_comision
            {$autorizadaSql}
            ORDER BY comision.pfid ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
