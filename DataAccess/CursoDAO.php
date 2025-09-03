<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;
use SqlOrganize\Utils\ValueTypesUtils;
use Fines2\TomaDAO;
class CursoDAO
{

    public static function IdCursoByParams($pfid, $codigo, $calendario){
        $sql = "
            SELECT curso.id 
            FROM curso
            INNER JOIN disposicion ON curso.disposicion = disposicion.id
            INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
            INNER JOIN comision ON curso.comision = comision.id
            WHERE comision.pfid = :pfid
            AND comision.calendario = :calendario
            AND asignatura.codigo LIKE :codigo
            ";

        $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();
        return $dataProvider->fetchSqlValueByParams($sql, ["pfid"=>$pfid, "calendario"=>$calendario, "codigo"=>"%$codigo%"]);

    }

    public static function CursosAutorizadosPublicadosByCalendario($calendario): array {
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT curso.id
            FROM curso 
            INNER JOIN comision ON (comision.id = curso.comision)
            WHERE comision.autorizada = true 
            AND comision.publicada = true 
            AND comision.calendario = :calendario
            ORDER BY comision.pfid ASC;
        ";

      return $dataProvider->fetchAllEntitiesBySqlId("curso", $sql, ["calendario" => CALENDARIO_ID_ACTUAL]);
    }

    public static function CursosByCalendario($calendario): array {
        $db = \App\Context::getFinesDb();

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
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT curso.id
            FROM curso 
            INNER JOIN comision ON (comision.id = curso.comision)
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
        $db = \App\Context::getFinesDb();

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

    /**
     * En una comision puede haber cursos de diferente planificacion (por ejemplo aquellos que quedaron pendientes, que hacen dos años en uno o que tienen materias previas).
     */
    public static function CursosConTomasAprobadasYPendientesByComision($comision): array {
        $db = \App\Context::getFinesDb();

        $dataProvider = $db->CreateDataProvider();

        $cursos = $dataProvider->fetchAllEntitiesByParams("curso", ["comision" => $comision]);
        $id_cursos = ValueTypesUtils::arrayOfName($cursos, "id");

        $tomasActivas = TomaDAO::TomasAprobadasYPendientesByCursos(...$id_cursos);
        $tomasActivas = ValueTypesUtils::dictOfObjByPropertyNames($tomasActivas, "curso");

        foreach($cursos as &$curso){
            if(array_key_exists($curso->id, $tomasActivas))
                $curso->setFk("toma_activa", $tomasActivas[$curso->id]);
        }

        return $cursos;
    }

    

}