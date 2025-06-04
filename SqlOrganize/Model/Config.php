<?php

namespace SqlOrganize\Model;

/**
 * Configuración
 */
class Config
{
    /**
     * Conexión con la base de datos
     */
    public string $host;
    public string $dbName;
    public string $user;
    public string $pass;
    
    public array $reservedAlias = [];
    public array $reservedEntities = [];
    public array $dontTreatAsFk = [];
    public array $limitToFirstLevel = [];

    public string $namespace;

    public string $idSource = "field_name";

    public string $dataClassesPath = "";

    /**
     * Nombre del identificador único de las tablas
     * 
     * Todas las tablas deben tener un identificador, que puede ser real o ficticio.
     * El identificador ficticio se define como "_Id"
     */
    public string $idName = "id";
    /**
     * String de concatenacion
     * 
     * Se utiliza para ciertas operaciones como por ejemplo la concatenación de campos
     */
    public string $concatString = "~";
    
    /**
     * fk asociada a id
     * 
     * Para algunas base de datos las fk están directamente asociadas a las Id.
     * Se debe indicar para que las operaciones sean mas eficientes
     */
    public bool $fkId = true;
    
    public string $separator = "__";

    public string $schemaClassPath;

    public string $dbClass = "\SqlOrganize\Sql\DbMy";

}