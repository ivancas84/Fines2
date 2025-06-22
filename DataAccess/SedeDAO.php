<?php
namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;

class SedeDAO {

    public static function referentesLabelByIdSedes(array $ids_sedes): array {
        $dataProvider = DbMy::getInstance()->CreateDataProvider();
        $referentes = $dataProvider->fetchAllEntitiesByParams("designacion", ["cargo" => Cargo_::$referente_id, "sede" => $ids_sedes]);
        $referentes = ValueTypesUtils::dictOfListByPropertyName($referentes, "sede");
        foreach($referentes as $key => $referente){
            echo "sede $key tiene " . count($referente) . " referentes.\n";
            foreach($referente as $r) {
                print_r($r->toArray());
            }
        }
        
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