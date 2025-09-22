<?php

namespace Fines2;

use App\Context;
use Fines2\AlumnoComision_;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\ModifyQueries;

class AlumnoComisionDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, string $alumno_id, string $comision_id, ?string $observaciones): AlumnoComision_{

        /** @var Db */ $db = Context::getFinesDb();
        /** @var AlumnoComision_ */ $alumnoComision = $db->CreateDataProvider()->fetchEntityByParams("alumno_comision", ["alumno" => $alumno_id, "comision" => $comision_id]); 
        if($alumnoComision == null ) {
            $alumnoComision = new AlumnoComision_();
            $alumnoComision->_status = -1; //marco para insertar
            $alumnoComision->set("alumno", $alumno_id);
            $alumnoComision->set("comision", $comision_id);
            $alumnoComision->set("estado", ($modifyQueries->getDetailAction("alumno", $alumno_id) == "insert") ? "Ingresante" : "Incorporado");
            $alumnoComision->set("observaciones", $observaciones);
            $modifyQueries->buildInsertSql($alumnoComision);
        }
        return $alumnoComision;
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
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("alumno_comision", $sql, ['alumno' => $alumno_id]);
    }

}