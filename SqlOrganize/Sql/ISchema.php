<?php

namespace SqlOrganize\Sql;

/**
 * Configuracion del esquema de la base de datos
 */
class ISchema
{

     public array $entities;

    /**
     * JSON con entidades del modelo
     * 
     * Se genera a traves de proyecto ModelOrganize
     * 
     * @return array<string, EntityMetadata>
     */
    public function getEntities(): array{
        return $this->entities;

    }
    
   
    
}