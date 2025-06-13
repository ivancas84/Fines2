<?php

namespace Fines2;

use \Fines2\Curso;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;
class Curso_ extends Curso
{
/** @var string|null */
    public ?string $toma_activa = null;

    /** @var Toma|null (fk ficticia curso.toma_activa _o:o toma.id) */
    public ?\Fines2\Toma_ $toma_activa_ = null;


    
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
        
        $cursos = self::CursosActivosByCalendario($calendario);
        $tomasActivas = Toma_::TomasActivasByCalendario($calendario);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($cursos as &$curso){
            if(array_key_exists($curso->id, $tomasActivas))
                $curso->setFkObj("toma_activa", $tomasActivas[$curso->id]);
        }

        return $cursos;

    }
}

