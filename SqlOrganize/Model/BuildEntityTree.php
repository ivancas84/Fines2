<?php

namespace SqlOrganize\Model;

require_once MAIN_PATH . 'SqlOrganize/Model/EntityMetadata.php';
require_once MAIN_PATH . 'SqlOrganize/Model/EntityTree.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Field.php';


class BuildEntityTree
{
   
    public Config $config;
    /**
     * @var array<string, EntityMetadata>
     */
    public array $entities;
    
    /**
     * @var array<string, array<string, Field>>
     */
    public array $fields;
    
    public string $entityName;
    
    protected array $fieldIds = [];
    
    protected array $allEntitiesVisited = [];
    
    public function __construct(Config $config, array $entities, array $fields, string $entityName)
    {    
        $this->config = $config;
        $this->entities = $entities;
        $this->entityName = $entityName;
        $this->fields = $fields;
    }
    
    /**
     * @return array<string, EntityTree>
     */
    public function build(): array
    {
        if (empty($this->entities[$this->entityName]->fk)) {
            return [];
        }
        
        $entitiesVisited = [];
        return $this->fk($this->entities[$this->entityName], $entitiesVisited);
    }
    
    protected function getFieldId(string $name, ?string $alias = null, string $separator = "_"): string
    {
        if (!in_array($name, $this->fieldIds)) {
            $this->fieldIds[] = $name;
            return $name;
        }
        
        // Generar nombre base con alias
        $suffix = "";
        if (!empty($alias)) {
            $suffix = (strlen($alias) >= 3) ? substr($alias, 0, 3) : $alias;
            $name = $name . $separator . $suffix;
        }
        
        if (!in_array($name, $this->fieldIds)) {
            $this->fieldIds[] = $name;
            return $name;
        }
        
        // Bucle seguro con nombre base
        $baseName = $name;
        $counter = 1;
        do {
            $candidate = $baseName . $counter;
            $counter++;
        } while (in_array($candidate, $this->fieldIds));
        
        $this->fieldIds[] = $candidate;
        return $candidate;
    }
    
    /**
     * @param EntityMetadata $entity
     * @param array $entitiesVisited
     * @param string|null $alias
     * @return array<string, EntityTree>
     */
    protected function fk(EntityMetadata $entity, array $entitiesVisited, ?string $alias = null): array
    {
        if (in_array($entity->name, $entitiesVisited) || 
            (in_array($entity->name, $this->allEntitiesVisited) && 
             in_array($entity->name, $this->config->limitToFirstLevel))) {
            return [];
        }
        
        $fk = $this->fieldsFkNotReferenced($entity, $entitiesVisited);
        
        $entitiesVisited[] = $entity->name;
        $this->allEntitiesVisited[] = $entity->name;
        
        $dict = [];
        
        foreach ($fk as $field) {
            $idSource = ($this->config->idSource === "field_name") ? $field->name : $field->refEntityName;
            $fieldId = $this->getFieldId($idSource, $alias);
            
            if (in_array($field->refEntityName, $this->allEntitiesVisited) && 
                in_array($field->refEntityName, $this->config->limitToFirstLevel)) {
                continue;
            }
            
            $tree = new EntityTree();
            $tree->fieldId = $fieldId;
            $tree->entityName = $entity->name;
            $tree->fieldName = $field->name;
            $tree->refEntityName = $field->refEntityName;
            $tree->refFieldName = $field->refFieldName;
            
            $dict[$fieldId] = $tree;
        }
        
        foreach ($dict as $fieldId => $tree) {
            $tree->children = $this->fk($this->entities[$tree->refEntityName], $entitiesVisited, $fieldId);
        }
        
        return $dict;
    }
    
    /**
     * Obtener FK de la entidad que no han sido referenciadas
     * 
     * @param EntityMetadata $e
     * @param array $referencedEntityNames
     * @return array<Field>
     */
    public function fieldsFkNotReferenced(EntityMetadata $e, array $referencedEntityNames): array
    {
        $fields = [];
        
        foreach ($e->fk as $fieldName) {
            $field = $this->fields[$e->name][$fieldName];
            if (!empty($field->refEntityName) && !in_array($field->refEntityName, $referencedEntityNames)) {
                $fields[] = $field;
            }
        }
        
        return $fields;
    }
}