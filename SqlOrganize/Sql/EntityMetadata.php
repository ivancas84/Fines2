<?php

namespace SqlOrganize\Sql;

require_once MAIN_PATH . 'SqlOrganize/Utils/ValueTypesUtils.php';

use SqlOrganize\Utils\ValueTypesUtils;

class EntityMetadata
{
    public Db $db;
    public string $name;
    public string $alias;
    public ?string $schema = null;
    
    private ?string $_className = null;
    
    public static function getInstance($name, $alias){
        $em = new EntityMetadata();
        $em->name = $name;
        $em->alias = $alias;
        return $em;
    }

    public function getClassName(): string
    {
        return empty($this->_className) 
            ? ValueTypesUtils::toCamelCase($this->name)
            : $this->_className;
    }

    public function getClassNameWithNamespace(): string
    {
        $namespace = (!empty($this->db->config->namespace)) ? "\\".$this->db->config->namespace ."\\" : "";
        return $namespace . $this->getClassName();
    }
    
    public function setClassName(string $className): void
    {
        $this->_className = $className;
    }
    
    /** @var string[] */
    public array $pk = [];
    
    /** @var string[] */
    public array $fk = [];
    
    /** @var Field[] */
    protected ?array $_ref = []; // ref
    
    /** @var Field[] */
    protected ?array $_oor = []; // one to one (fk in ref)
    
    /** @var Field[] */
    protected ?array $_om = []; // one to many
    
    /**
     * Array dinamico para identificar a la entidad en un momento determinado
     * @example ["fecha_anio", "fecha_semestre","persona__numero_documento"]
     * @var string[]
     */
    public array $identifier = [];
    
    /**
     * Valores por defecto para ordenamiento
     * @example ["field1" => "asc", "field2" => "desc", ...]
     * @var array<string, string>
     */
    public array $orderDefault = [];
    
    /**
     * nombres de campos no administrables
     * los campos no insertables son aquellos que reciben se asignan directamente desde el servidor sql
     * @var string[]
     */
    public array $noAdmin = [];
    
    /**
     * Valores principales
     * @var string[]
     */
    public array $main = [];
    
    /**
     * Valores unicos
     * Una entidad puede tener varios campos que determinen un valor único
     * @example field1, field2, field3, ...
     * @var string[]
     */
    public array $unique = [];
    
    /**
     * Valores no nulos
     * @var string[]
     */
    public array $notNull = [];
    
    /**
     * Valores unicos multiples
     * Cada juego de valores unicos multiples se define como una Lista
     * @var string[][]
     */
    public array $uniqueMultiple = [];
    
    /** @var array<string, EntityTree> */
    public array $tree = [];
    
    /** @var array<string, EntityRelation> */
    public array $relations = [];
    
    /** @var array<string, EntityRef> */
    public array $oo = [];
    
    /** @var array<string, EntityRef> */
    public array $om = [];
    
    /**
     * Campo de identificacion
     * Si existe un solo campo pk, entonces la pk sera el id.
     * Si existe al menos un campo unique not null, se toma como id.
     * Si existe multiples campos pk, se toman la concatenacion como id.
     * Si existe multiples campos uniqueMultiple, se toman la concatenacion como id.
     * @var string[]
     */
    public array $id = [];
    
    /** @var array<string, Field> */
    public array $fields = [];
    
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }
    
    public function getSchema_(): string
    {
        return empty($this->schema) ? "" : $this->schema;
    }
    
    public function getSchemaName(): string
    {
        return $this->schema . $this->name;
    }
    
    public function getSchemaNameAlias(): string
    {
        return $this->schema . $this->name . " AS " . $this->alias;
    }
    
    /**
     * @param string[] $fieldNames
     * @return Field[]
     */
    protected function _Fields(array $fieldNames): array
    {
        $fields = [];
        foreach ($fieldNames as $fieldName) {
            $fields[] = $this->db->Field($this->name, $fieldName);
        }
        return $fields;
    }
    
    /**
     * fields no fk
     * @return Field[]
     */
    public function Fields(): array
    {
        return $this->_Fields($this->getFieldNames());
    }
    
    /**
     * fields many to one
     * @return Field[]
     */
    public function FieldsFk(): array
    {
        return $this->_Fields($this->fk);
    }
    
    /**
     * @return Field[]
     */
    public function FieldsRef(): array
    {
        if ($this->_ref !== null) {
            return $this->_ref;
        }
        
        $this->_ref = [];
        $this->_oor = [];
        $this->_om = [];
        
        foreach ($this->db->entities as $entityName => $entity) {
            foreach ($entity->fk as $fieldName) {
                $field = $this->db->Field($entityName, $fieldName);
                if ($field->refEntityName === $this->name) {
                    $this->_ref[] = $field;
                    if (in_array($field->name, $entity->unique)) {
                        $this->_oor[] = $field;
                    } else {
                        $this->_om[] = $field;
                    }
                }
            }
        }
        
        return $this->_ref;
    }
    
    /**
     * @return Field[]
     */
    public function FieldsOor(): array
    {
        if ($this->_oor !== null) {
            return $this->_oor;
        }
        
        $this->FieldsRef();
        return $this->_oor;
    }
    
    /**
     * @return Field[]
     */
    public function FieldsOm(): array
    {
        if ($this->_om !== null) {
            return $this->_om;
        }
        
        $this->FieldsRef();
        return $this->_om;
    }
    
    public function Pf(?string $fieldId = null): string
    {
        return !empty($fieldId) ? $fieldId . $this->db->config->separator : "";
    }
    
    public function Pt(?string $fieldId = null): string
    {
        return !empty($fieldId) ? $fieldId : $this->alias;
    }
    
    public function Map(string $fieldName, ?string $fieldId = null): string
    {
        return $this->Pt($fieldId) . "." . $fieldName;
    }
    
    public function CleanPf(string $fieldName): string
    {
        $pf = $this->Pf();
        if (!empty($pf) && str_contains($fieldName, $pf)) {
            return str_replace($pf, "", $fieldName);
        }
        return $fieldName;
    }
}