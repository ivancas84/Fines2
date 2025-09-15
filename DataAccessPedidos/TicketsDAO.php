<?php

namespace Fines2;

use SqlOrganize\Sql\ModifyQueries;

class TicketsDAO
{

    public static function createAndPersist(ModifyQueries $modifyQueries, string $alumno_id, string $comision_id, ?string $observaciones): AlumnoComision_{
        $dbPedidos = \App\Context::getPedidosDb();
        $dataProvider = $dbPedidos->CreateDataProvider();
        $ticket_id = $dataProvider->getNextId("tickets");
        $thread_id = $dataProvider->getNextId("threads");
        $attachment_id = $dataProvider->getNextId("attachments");
        $auth_code = generateAuthCode();

        $alumnoComision = new AlumnoComision_();
        $alumnoComision->initByUnique(["alumno" => $alumno_id, "comision" => $comision_id]);
        
        if ($alumnoComision->_status < 0){
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