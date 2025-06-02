<?php

namespace SqlOrganize\Sql;

class Field
{
    public Db $db;
    public string $name;
    public ?string $alias = null;
    
    /**
     * nombre de la entidad
     */
    public string $entityName;
    
    /**
     * si es clave foranea: Nombre de la entidad referenciada por la clave foranea
     */
    public ?string $refEntityName = null;
    
    /**
     * si es clave foranea: Nombre del field al que hace referencia de la entidad referenciada
     */
    public ?string $refFieldName = "id";
    
    /**
     * Tipo de datos del motor
     */
    public string $dataType = "varchar";
    
    /**
     * Tipo de datos del lenguaje
     */
    public string $type = "string";
    
    /**
     * string con el tipo de field
     * "pk": Clave primaria
     * "nf": Field normal
     * "mo": Clave foranea muchos a uno
     * "oo": Clave foranea uno a uno
     */
    public string $fieldType = "nf";
    
    /**
     * Valor por defecto
     */
    public mixed $defaultValue = null;
    
    /**
     * @var array<string, mixed>
     */
    public array $checks = [];
    
    /**
     * @var array<string, mixed>
     */
    public array $resets = [];
    
    public function GetEntityMetadata(): EntityMetadata
    {
        return $this->db->GetEntityMetadata($this->entityName);
    }
    
    public function RefEntity(): EntityMetadata
    {
        return $this->db->GetEntityMetadata($this->refEntityName);
    }
    
    public function IsRequired(): bool
    {
        $entity = $this->db->GetEntityMetadata($this->entityName);
        return in_array($this->name, $entity->notNull);
    }
    
    /**
     * Permite el Field identificar univocamente a la entidad por sí solo?
     * 
     * @return bool true si el field permite identificar univocamente a la entidad por sí solo
     */
    public function IsUnique(): bool
    {
        $entity = $this->db->GetEntityMetadata($this->entityName);
        
        if (in_array($this->name, $entity->unique)) {
            return true;
        }
        
        if (in_array($this->name, $entity->pk) && count($entity->pk) === 1) {
            return true;
        }
        
        return false;
    }
}