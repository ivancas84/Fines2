<?php

namespace Fines2\DataAccess;

use App\Context;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision_;
use PDO;

class AlumnoComisionDAO
{

    /**
     * @return AlumnoComision_[]
     */
    public static function alumnosComision($comision_id): array{
        $sql = "
            SELECT alumno_comision.id
            FROM alumno_comision
            INNER JOIN alumno ON (alumno_comision.alumno = alumno.id)
            INNER JOIN persona ON (alumno.persona = persona.id)
            WHERE alumno_comision.comision = :comision
            ORDER BY alumno_comision.activo DESC, persona.apellidos ASC, persona.nombres ASC;
        ";
        $comisiones = Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("alumno_comision", $sql, ['comision' => $comision_id]);
        return $comisiones;
    }
    
    /**
     * Ultima comisión activa de un alumno
     */
    public static function ultimaComisionAlumno($alumno_id): ?AlumnoComision_{
        $sql = "
            SELECT alumno_comision.id
			FROM alumno_comision
            INNER JOIN comision ON alumno_comision.comision = comision.id
            INNER JOIN calendario ON comision.calendario = calendario.id
            WHERE alumno_comision.alumno = :alumno
            AND alumno_comision.activo = true
            ORDER BY calendario.anio DESC, calendario.semestre DESC;
        ";
        return Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("alumno_comision", $sql, ['alumno' => $alumno_id]);
    }

    /**
     * @return Array
     * (
     *     [12] => Array
     *         (
     *             [cantidad_alumnos] => 30
     *             [cantidad_alumnos_activos] => 25
     *         )
     * 
     *     [15] => Array
     *         (
     *             [cantidad_alumnos] => 28
     *             [cantidad_alumnos_activos] => 20
     *         )
     * )
     */
    public static function cantidadAlumnosComisionCalendario(mixed $calendario_id): array{
        $sql = "
            SELECT 
            alumno_comision.comision,
            COUNT(alumno_comision.id) AS cantidad_alumnos,
            SUM(alumno_comision.activo = 1) AS cantidad_alumnos_activos
            FROM alumno_comision
            INNER JOIN comision ON (alumno_comision.comision = comision.id)
            WHERE comision.calendario = :calendario
            GROUP BY alumno_comision.comision;
        ";
        $stmt = Context::getFinesDb()->getPdo()->prepare($sql);
        $stmt->execute(['calendario' => $calendario_id]);

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['comision']] = [
                'cantidad_alumnos' => $row['cantidad_alumnos'],
                'cantidad_alumnos_activos' => $row['cantidad_alumnos_activos']
            ];
        }

        return $result;
    }


}