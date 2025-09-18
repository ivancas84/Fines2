<?php

namespace Fines2;

use SqlOrganize\Sql\ModifyQueries;

class TicketsDAO
{

    public static function TicketByFilepath($filepath){
        $sql = "
            SELECT ticket_id
            FROM wpwt_psmsc_attachments
            WHERE filepath = :filepath
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