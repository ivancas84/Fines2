<?php

namespace Fines2;

use SqlOrganize\Sql\DbMy;

class TomaDAO
{

    /**
     * @return string[]
     */
    public static function estados(): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        return $dataProvider->fetchAllColumnByParams("toma", "estado", [], ["estado" => "ASC"]);
    }

    /**
     * @return string[]
     */
    public static function tiposMovimientos(): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        return $dataProvider->fetchAllColumnByParams("toma", "tipo_movimiento", [], ["tipo_movimiento" => "ASC"]);
    }

    /**
     * @return string[]
     */
    public static function estadosContralor(): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        return $dataProvider->fetchAllColumnByParams("toma", "estado_contralor", [], ["estado_contralor" => "ASC"]);
    }

    public static function TomasByComision($idComision): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            WHERE comision = :comision
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["comision" => $idComision]);
    }

    public static function TomasByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            WHERE calendario = :calendario
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
    }

    public static function TomasActivasByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            WHERE calendario = :calendario
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
    }

    public static function TomasActivasBySedeAndCalendario($sede, $calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            INNER JOIN sede ON (comision.sede = sede.id)
            INNER JOIN calendario ON (comision.calendario = calendario.id)
            WHERE sede = :sede AND calendario = :calendario;
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["calendario" => $calendario, "sede" => $sede]);
    }

    public static function TomaActivaByCurso($curso_id): Toma_{
         $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            WHERE (toma.estado = 'Aprobada') 
            AND toma.estado_contralor = 'Pasar'
            AND curso.id = :curso_id
        ";

        return $dataProvider->fetchEntityBySqlId("toma", $sql, ["curso_id" => $curso_id]);
    }

    public static function TomasActivasByCursos(string ...$ids_cursos): array {
         $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            WHERE (toma.estado = 'Aprobada') 
            AND toma.estado_contralor = 'Pasar'
            AND curso.id IN (:ids_cursos)
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["ids_cursos" => $ids_cursos]);
    }

    public static function TomasAprobadasYPendientesByCursos(string ...$ids_cursos): array {
         $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            WHERE (toma.estado = 'Aprobada' OR toma.estado = 'Pendiente') 
            AND toma.estado_contralor = 'Pasar'
            AND curso.id IN (:ids_cursos)
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["ids_cursos" => $ids_cursos]);
    }

    public static function TomasContralorByCalendario($calendario): array {
        $db = DbMy::getInstance();

        $dataProvider = $db->CreateDataProvider();

        $sql = "
            SELECT DISTINCT toma.id 
            FROM toma
            INNER JOIN curso ON (toma.curso = curso.id)
            INNER JOIN comision ON (curso.comision = comision.id)
            INNER JOIN persona ON (toma.docente = persona.id)
            WHERE comision.calendario = :calendario
            AND (toma.estado = 'Aprobada') 
            AND toma.estado_contralor = 'Pasar'
            AND (toma.planilla_docente IS NULL OR toma.reclamo = true)
            ORDER BY persona.numero_documento ASC;
        ";

        return $dataProvider->fetchAllEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
    }
}