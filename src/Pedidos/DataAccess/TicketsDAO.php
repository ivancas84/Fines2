<?php

namespace Pedidos\DataAccess;

use DateTime;
use Pedidos\Model\Attachments_;
use Pedidos\Model\Threads_;
use Pedidos\Model\Tickets_;
use SqlOrganize\Sql\ModifyQueries;

class TicketsDAO
{


    /**
     * Crear e insertar un ticket de constancia y todos sus elementos relacionados
     */
    public static function CreateAndInsertTicketConstancia(ModifyQueries $modify, $titulo, $numero_documento, $body, $filename, $save_path): Tickets_{
        $ticket = new Tickets_();
        $ticket->subject = $titulo;
        $ticket->status = 4;
        $ticket->category = 10;
        $ticket->date_closed = new DateTime();
        $ticket->cust_24 = $numero_documento;
        $ticket->cust_28 = "Válido por 30 días";

        $thread = new Threads_();
        $thread->ticket = $ticket->id;
        $thread->body = $body;

        $attachment = new Attachments_();
        $attachment->name = $filename;
        $attachment->file_path = $save_path;
        $attachment->is_image = 1;
        $attachment->source_id = $thread->id;
        $attachment->ticket_id = $ticket->id;

        $modify->insertSql($ticket);
        $modify->insertSql($thread);
        $modify->insertSql($attachment);

        $thread->attachments = $attachment->id;
        $modify->updateKeySqlById($thread, "attachments");

        return $ticket;
    }
    
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
        return \App\Context::getFinesDb()->CreateDataProvider()->fetchEntityBySqlId("alumno_comision", $sql, ['alumno' => $filepath]);
    }

    
        
}