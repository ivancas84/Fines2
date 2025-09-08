<?php

namespace Fines2;

use Fines2\AlumnoComision_;
use SqlOrganize\Sql\ModifyQueries;

class AlumnoComisionDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, string $alumno_id, string $comision_id, ?string $observaciones): AlumnoComision_{
        
        $alumnoComision = new AlumnoComision_();
        $alumnoComision->initByUnique(["alumno" => $alumno_id, "comision" => $comision_id]);
        
        if ($alumnoComision->_status < 0){
            $alumnoComision->set("estado", ($modifyQueries->getDetailAction("alumno", $alumno_id) == "insert") ? "Ingresante" : "Incorporado");
            $alumnoComision->set("observaciones", $observaciones);
            $modifyQueries->buildInsertSql($alumnoComision);
        }
        return $alumnoComision;
    }

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
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("alumno_comision", $sql, ['alumno' => $alumno_id]);
    }
}