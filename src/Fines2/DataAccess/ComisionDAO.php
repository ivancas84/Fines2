<?php

namespace Fines2\DataAccess;

use App\Context;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;

class CollectComisionIdsResult
{
    public bool $comisiones_mezcladas;
    public bool $comisiones_diferente_plan;

    /** @var string[] */ public array $id_comisiones;

    public function __construct(
        bool $comisiones_mezcladas,
        bool $comisiones_diferente_plan,
        array $id_comisiones
    ) {
        $this->comisiones_mezcladas = $comisiones_mezcladas;
        $this->comisiones_diferente_plan = $comisiones_diferente_plan;
        $this->id_comisiones = $id_comisiones;
    }
}

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

        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("comision", $sql, ["calendario"=>$calendarioId] );
    }

    /**
     * @return Comision_[]
     */
    public static function comisionesConSiguienteSin32ByCalendario($calendarioId): array {

        $sql = "
            SELECT DISTINCT comision.id
            FROM comision
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            INNER JOIN planificacion ON (comision.planificacion = planificacion.id)
            WHERE CONCAT(planificacion.anio,planificacion.semestre) != '32'
            AND comision.comision_siguiente IS NOT NULL
            AND calendario.id = :calendario
        ";  

        return \App\Context::getFinesDb()->CreateDataProvider()->fetchAllEntitiesBySqlId("comision", $sql, ["calendario"=>$calendarioId] );
    }
   
    public static function collectComisionIdsByPfid(
        $pfid,
        array &$visited = [],
        bool &$mezcladas = false,
        bool &$diferentePlan = false
    ): CollectComisionIdsResult {

        /** @var DataProvider */
        $dp = Context::getFinesDb()->CreateDataProvider();

        /** @var Comision_[] */
        $comisiones = $dp->fetchAllEntitiesByParams("comision", ["pfid" => $pfid]);

        foreach ($comisiones as $comision)
            if (!in_array($comision->id, $visited, true))
                $visited[] = $comision->id;

        foreach ($comisiones as $comision) {

            $idComisionSiguiente = $comision->comision_siguiente;

            if (!empty($idComisionSiguiente) && !in_array($idComisionSiguiente, $visited)) {

                // this means mezcladas
                $mezcladas = true;

                /** @var Comision_ */
                $comisionSiguiente = $dp->fetchEntityByParams("comision", ["id" => $idComisionSiguiente]);

                if ($comisionSiguiente) {

                    if (
                        $comisionSiguiente->planificacion_->plan != $comision->planificacion_->plan
                    ) {
                        $diferentePlan = true;
                    }

                    // recurse
                    self::collectComisionIdsByPfid(
                        $comisionSiguiente->pfid,
                        $visited,
                        $mezcladas,
                        $diferentePlan
                    );
                }
            }
        }

        return new CollectComisionIdsResult(
            $mezcladas,
            $diferentePlan,
            $visited
        );
    }
   
}