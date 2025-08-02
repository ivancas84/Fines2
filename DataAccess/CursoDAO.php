<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;
use Fines2\TomaDAO;
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

        return $dataProvider->fetchAllEntitiesBySqlId("curso", $sql, ["calendario" => $calendario]);
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

        return $dataProvider->fetchAllEntitiesBySqlId("curso", $sql, ["calendario" => $calendario]);
    }

    public static function CursosActivosConTomasActivasByCalendario($calendario): array {
        
        $cursos = self::CursosActivosByCalendario($calendario);
        $tomasActivas = TomaDAO::TomasActivasByCalendario($calendario);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($cursos as &$curso){
            if(array_key_exists($curso->id, $tomasActivas))
                $curso->setFk("toma_activa", $tomasActivas[$curso->id]);
        }

        return $cursos;

    }

    /**
     * En una comision puede haber cursos de diferente planificacion (por ejemplo aquellos que quedaron pendientes, que hacen dos años en uno o que tienen materias previas).
     */
    public static function CursosConTomasActivasByComision($comision): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision" => $comision]);
        $id_cursos = ValueTypesUtils::arrayOfName($cursos, "id");

        $tomasActivas = TomaDAO::TomasActivasByCursos(...$id_cursos);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($cursos as &$curso){
            if(array_key_exists($curso->id, $tomasActivas))
                $curso->setFk("toma_activa", $tomasActivas[$curso->id]);
        }

        return $cursos;
    }

}