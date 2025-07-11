<?php
namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

class DisposicionDAO {

    /**
     *  @return Disposicion_[]
     */
    public static function disposicionesByPlanTramo($plan, $tramo_short): array {
        $sql = "SELECT disposicion.id
                FROM disposicion
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                WHERE planificacion.plan = :plan
                AND CONCAT(planificacion.anio, planificacion.semestre) >= :tramo_short;";

        return DbMy::getInstance()->CreateDataProvider()
            ->fetchAllEntitiesBySqlId("disposicion", $sql, ["plan" => $plan, "tramo_short" => $tramo_short]);
    }

}