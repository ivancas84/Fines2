<?php

namespace SqlOrganize\Sql;

/**
 * Configuracion del esquema de la base de datos
 */
interface ISchema
{
    /**
     * JSON con entidades del modelo
     * 
     * Se genera a traves de proyecto ModelOrganize
     * 
     * @return array<string, EntityMetadata>
     */
    public function getEntities(): array;
    
    /**
     * Establece las entidades del esquema
     * 
     * @param array<string, EntityMetadata> $entities
     */
    public function setEntities(array $entities): void;
    
}