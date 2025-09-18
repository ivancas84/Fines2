<?php

namespace Fines2;

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

    
        
}