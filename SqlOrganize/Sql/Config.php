<?php

namespace SqlOrganize\Sql;

/**
 * Configuración
 */
class Config
{
    /**
     * Conexión con la base de datos
     */
    public string $connectionString;
    public string $username;
    public string $password;

    
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
    
    /**
     * Nombre del identificador único de las tablas
     * 
     * Todas las tablas deben tener un identificador, que puede ser real o ficticio.
     * El identificador ficticio se define como "_Id"
     */
    public string $id = "_Id";
    
    /**
     * Nombre de la base de datos
     */
    public string $dbName;
    
    public string $separator = "__";
}