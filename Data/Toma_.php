<?php

namespace Fines2;

use \Fines2\Toma;

use SqlOrganize\Sql\DbMy;

class Toma_ extends Toma
{
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

        return $dataProvider->fetchEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
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

        return $dataProvider->fetchEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
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

        return $dataProvider->fetchEntitiesBySqlId("toma", $sql, ["calendario" => $calendario]);
    }
}

