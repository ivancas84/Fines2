<?php

namespace Fines2;

use \SqlOrganize\Sql\DbMy;
use \SqlOrganize\Utils\ValueTypesUtils;
 
class CursoDAO
{


    public static function CursosByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT curso.id
            FROM curso 
            INNER JOIN comision ON (comision.id = curso.comision)
            INNER JOIN planificacion ON (planificacion.id = comision.planificacion)
            AND comision.calendario = :calendario
        ";

        return $dataProvider->fetchEntitiesBySqlId("curso", $sql, ["calendario" => $calendario]);
    }

    public static function CursosActivosByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT curso.id
            FROM curso 
            INNER JOIN comision ON (comision.id = curso.comision)
            INNER JOIN planificacion ON (planificacion.id = comision.planificacion)
            WHERE comision.autorizada
            AND comision.calendario = :calendario
        ";

        return $dataProvider->fetchEntitiesBySqlId("curso", $sql, ["calendario" => $calendario]);
    }

    public static function CursosActivosConTomasActivasByCalendario($calendario): array {
        
        $cursos = CursoDAO::CursosActivosByCalendario($calendario);
        $tomasActivas = TomaDAO::TomasActivasByCalendario($calendario);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($cursos as &$curso){
            if(array_key_exists($curso->id, $tomasActivas))
                $curso->setFkObj("toma_activa", $tomasActivas[$curso->id]);
        }

        return $cursos;

    }

}