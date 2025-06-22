<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;

class CalendarioDAO {

    public static function calendarios(): array {

        $sql = "SELECT id
            FROM calendario 
            ORDER BY anio DESC, semestre DESC;";

        return DbMy::getInstance()->CreateDataProvider()->fetchAllEntitiesBySqlId("calendario", $sql);
    }
}