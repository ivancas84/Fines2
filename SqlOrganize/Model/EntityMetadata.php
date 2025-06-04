<?php

namespace SqlOrganize\Model;

class EntityMetadata
{
    public string $name;

    public string $alias;
    public ?string $schema = null;
    
    public array $pk = [];
    public array $fields = [];
    public array $fk = [];
    
    /**
     * Valores por defecto para ordenamiento
     * @example ["field1" => "asc", "field2" => "desc", ...]
     */
    public array $orderDefault = [];
    
    /**
     * Valores no administrables
     * @example ["field1", "field2", ...]
     */
    public array $noAdmin = [];
    
    /**
     * Valores únicos
     * Una entidad puede tener varios campos que determinen un valor único
     * @example ["field1", "field2", ...]
     */
    public array $unique = [];
    
    /**
     * Valores no nulos
     */
    public array $notNull = [];
    
    /**
     * Valores únicos múltiples
     * Solo puede especificarse un juego de campos unique_multiple
     */
    public array $uniqueMultiple = [];
    
    /**
     * Campo de identificación
     * - Si existe un solo campo pk, entonces la pk será el id
     * - Si existe al menos un campo unique not null, se toma como id
     * - Si existe múltiples campos pk, se toman la concatenación como id
     * - Si existe múltiples campos uniqueMultiple, se toman la concatenación como id
     */
    public array $id = [];
    
    /**
     * @var array<string, EntityTree>
     */
    public array $tree = [];
    
    /**
     * @var array<string, EntityRelation>
     */
    public array $relations = [];
    
    /**
     * @var array<string, EntityRef>
     */
    public array $oo = [];
    
    /**
     * @var array<string, EntityRef>
     */
    public array $om = [];
    
    public function __construct()
    {
        $this->pk = [];
        $this->fields = [];
        $this->fk = [];
        $this->orderDefault = [];
        $this->noAdmin = [];
        $this->unique = [];
        $this->notNull = [];
        $this->uniqueMultiple = [];
        $this->id = [];
        $this->tree = [];
        $this->relations = [];
        $this->oo = [];
        $this->om = [];
    }
}