<?php

namespace Pedidos\DataAccess;

use SqlOrganize\Sql\ModifyQueries;

class AttachmentsDAO
{

    public static function TicketIdByFilepath($file_path): ?int {
        $sql = "
            SELECT ticket_id
            FROM wpwt_psmsc_attachments
            WHERE file_path = :file_path
        ";
        return \App\Context::getPedidosDb()->CreateDataProvider()->fetchSqlValueByParams($sql, ['file_path' => $file_path]);
    }

    public static function CheckTicketIdByFilepath($file_path): void {
        $ticketId = self::TicketIdByFilepath($file_path);

        $dbPedidos = \App\Context::getPedidosDb();

        if(!is_null($ticketId)){
            /** @var Tickets_ */$ticket = $dbPedidos->CreateDataProvider()->fetchEntityByParams("tickets", ["id" => $ticketId]);
            $url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" . $ticket->id . "&auth-code=" . $ticket->auth_code;
            echo "<p>Ya existe una constancia generada para este DNI. Puede descargarla nuevamente desde el siguiente enlace:</p>";
            echo "<p><a href='$url' target='_blank'>$url</a></p>";
            die();
        }

    }

    
        
}