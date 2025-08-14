<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
class ComisionDAO
{
/**
     * @return Comision_[]
     */
    public static function comisionesAutorizadasSin32ByCalendario($calendarioId): array {

        $sql = "
            SELECT DISTINCT comision.id
            FROM comision
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            INNER JOIN planificacion ON (comision.planificacion = planificacion.id)
            WHERE CONCAT(planificacion.anio,planificacion.semestre) != '32'
            AND autorizada = 1
            AND calendario.id = :calendario
        ";  

        return DbMy::getInstance()->CreateDataProvider()->fetchAllEntitiesBySqlId("comision", $sql, ["calendario"=>$calendarioId] );
    }
   
}