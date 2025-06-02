<?php

namespace SqlOrganize\Model;

/**
 * Lectura de json
 */
class EntityTree
{
    public string $fieldId;
    public string $entityName;
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName;
    
    /**
     * @var array<string, EntityTree> Diccionario de hijos
     */
    public array $children = [];
    
    public function __construct()
    {
        $this->children = [];
    }
}