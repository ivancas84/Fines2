<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
 
class ModalidadDAO
{

    public static function modalidades(): array{
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT * FROM modalidad ORDER BY nombre ASC";
        return $dataProvider->fetchEntitiesBySqlId("modalidad", $sql);
    }

}