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

        $entities["alumno_comision"]->setQualifiedClassName("\\Fines2\\AlumnoComision_");
        $entities["calendario"]->setQualifiedClassName("\\Fines2\\Calendario_");
        $entities["calificacion"]->setQualifiedClassName("\\Fines2\\Calificacion_");
        $entities["cargo"]->setQualifiedClassName("\\Fines2\\Cargo_");
        $entities["centro_educativo"]->setQualifiedClassName("\\Fines2\\CentroEducativo_");
        $entities["comision"]->setQualifiedClassName("\\Fines2\\Comision_");
        $entities["curso"]->setQualifiedClassName("\\Fines2\\Curso_");
        $entities["designacion"]->setQualifiedClassName("\\Fines2\\Designacion_");
        $entities["disposicion"]->setQualifiedClassName("\\Fines2\\Disposicion_");
        $entities["domicilio"]->setQualifiedClassName("\\Fines2\\Domicilio_");
        $entities["modalidad"]->setQualifiedClassName("\\Fines2\\Modalidad_");
        $entities["planificacion"]->setQualifiedClassName("\\Fines2\\Planificacion_");
        $entities["persona"]->setQualifiedClassName("\\Fines2\\Persona_");
        $entities["sede"]->setQualifiedClassName("\\Fines2\\Sede_");

        $entities["alumno_comision"]->uniqueMultiple = [["alumno","comision"]];
        $entities["asignatura"]->main = ["nombre", "codigo"];
        $entities["calendario"]->main = ["anio", "semestre", "descripcion"];
        $entities["comision"]->main = ["division"];
        $entities["sede"]->main = ["nombre"];
        $entities["persona"]->main = ["nombres", "apellidos","numero_documento"];

        foreach ($entities as $entity)
            $entity->fields["id"]->defaultValue = "uniqid";
        
        return $entities;
    }
}
