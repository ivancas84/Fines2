<?php
namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

class DesignacionDAO {

    public static function referentesByIdSedes(array $ids_sedes): array {
        $sql = "SELECT designacion.id
                FROM designacion
                WHERE designacion.cargo = :cargo
                AND designacion.sede IN (:sede)
                AND designacion.hasta IS NULL;";

        return DbMy::getInstance()->CreateDataProvider()
            ->fetchAllEntitiesBySqlId("designacion", $sql, ["cargo" => Cargo_::$referente_id, "sede" => $ids_sedes]);
    }

     public static function referentesLabelByIdSedes(array $ids_sedes): array {
        /** @var Designacion_[] */ $referentes = self::referentesByIdSedes($ids_sedes);
        /** @var array<string, Designacion_[]> */ $referentes = ValueTypesUtils::dictOfListByPropertyName($referentes, "sede");
        $referentesSedeLabel = [];
        foreach($ids_sedes as $id_sede){
            if(array_key_exists($id_sede, $referentes)) {
                $referentesLabel = [];
                foreach($referentes[$id_sede] as $r)
                    $referentesLabel[] = $r->getLabel();

                $referentesSedeLabel[$id_sede] = implode(", ", $referentesLabel);
            } else {
                $referentesSedeLabel[$id_sede] = "Sin Referentes"; // No hay referente para esta sede
            }
        }
        
        return $referentesSedeLabel;
    }
}