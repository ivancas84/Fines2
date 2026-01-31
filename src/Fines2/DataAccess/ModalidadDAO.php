<?php

namespace Fines2\DataAccess;

use SqlOrganize\Sql\DbMy;

class ModalidadDAO
{
    public static function modalidades(): array{
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT * FROM modalidad ORDER BY nombre ASC";
        return $dataProvider->fetchAllEntitiesBySqlId("modalidad", $sql);
    }

}

