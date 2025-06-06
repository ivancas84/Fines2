<?php

namespace Fines2;

use \Fines2\Schema;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class Schema_
{
    public static function getEntities()
    {
        $entities = Schema::getEntities();
        $entities["comision"]->main = ["division"];
        $entities["sede"]->main = ["nombre"];
        $entities["calendario"]->main = ["anio", "semestre", "descripcion"];
        $entities["asignatura"]->main = ["nombre", "codigo"];
        $entities["persona"]->main = ["nombres", "apellidos","numero_documento"];

        return $entities;
    }
}
