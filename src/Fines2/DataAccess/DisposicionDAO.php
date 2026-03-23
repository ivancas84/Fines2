<?php
namespace Fines2\DataAccess;

use Fines2\Model\Disposicion_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;
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

        return \App\Context::getFinesDb()->CreateDataProvider()
            ->fetchAllEntitiesBySqlId("disposicion", $sql, ["plan" => $plan, "tramo_short" => $tramo_short]);
    }

    public static function disposicionesActuales(): array {
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT disposicion.id
            FROM disposicion
            INNER JOIN asignatura ON asignatura.id = disposicion.asignatura 
            INNER JOIN planificacion ON planificacion.id = disposicion.planificacion 
            INNER JOIN plan ON plan.id = planificacion.plan 
            WHERE plan.id IN ('202303101', '202303102', '4', '5', '2026032201') 
            ORDER BY asignatura.nombre, planificacion.anio, planificacion.semestre, plan.resolucion, plan.orientacion ASC;
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("disposicion", $sql);
    }

    /**
     * @return Disposicion_[]
     */
    public static function disposicionesDivision($comision_pfid): array{
        /** @var Db */ $db = \App\Context::getFinesDb();

        /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

        $sql = "
        SELECT DISTINCT disposicion.id
        FROM disposicion
        INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
        WHERE planificacion.plan IN (
            SELECT planificacion.plan
            FROM comision
            INNER JOIN planificacion ON comision.planificacion = planificacion.id
            WHERE comision.pfid = :comision_pfid
        )
        ORDER BY planificacion.anio, planificacion.semestre;
";
    
        return $dataProvider->fetchAllEntitiesBySqlId("disposicion", $sql, ["comision_pfid" => $comision_pfid]);
    }

}