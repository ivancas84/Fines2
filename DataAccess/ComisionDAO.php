<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
class ComisionDAO
{

    public static function comisionesByFilterAndOrder(array $filter, array $order){
        $db = DbMy::getInstance();
        $selectQueries = $db->CreateSelectQueries();
        $dataProvider = $db->CreateDataProvider();

        $sql = "SELECT id FROM comision";
        $sql .= $selectQueries->where

        if (!empty($filter)) {
            $sql .= " WHERE " . $dataProvider->buildWhereClause($filter);
        }

        if (!empty($order)) {
            $sql .= " ORDER BY " . $dataProvider->buildOrderClause($order);
        }

        return $dataProvider->fetchAllEntitiesBySqlId("comision", $sql);

    }

}