<?php

namespace SqlOrganize\Sql;

use PDO;
use PDOException;
use DateTimeInterface;
/**
 * Contenedor principal de SqlOrganize
 * 
 * Db utiliza y es utilizado como herramienta en varios patrones de diseño: AbstractFactory, AbstractCreator, AbstractBuilder, Singleton.
 * Una implementación de Db para un determinado motor de base de datos, sera el ConcreteFactory (Ej DbMy extends Db).
 * Una implementación de Db para una determinada App sera el ConcreteCreator (Ej DbApp extends DbMy).
 * En una determinada App existira una clase Container que sera el director (Builder) y utilizara clases estaticas de Db (Singleton).
 * Todos los elementos necesarios para conectarse, obtener y definir datos de una base, deben pertenecer a Db.
 */
abstract class Db 
{

    /** @var array<string, EntityMetadata> */
    public array $entities;

    public PDO $pdo;
    public Config $config;
    
    protected function __construct(Config $config, array $entities)
    {
        $this->config = $config;
        $this->initEntities($entities);
    }

    protected abstract function initPdo();
    
    protected function initEntities(array $entities){
        $this->entities = $entities;

        foreach ($this->entities as $entity) {
            $entity->db = $this;
            
            foreach ($entity->fields as $field) {
                $field->db = $this;
            }
        }
    }

  
    // Get the PDO connection
    public function getPdo(): PDO
    {
        if(empty($this->pdo)) $this->initPdo();
        return $this->pdo;
    }
    
    /**
     * @param string $entityName
     * @return array<string, Field>
     * @throws \Exception
     */
    public function FieldsEntity(string $entityName): array
    {
        if (!array_key_exists($entityName, $this->entities)) {
            throw new \Exception("La entidad " . $entityName . " no existe");
        }
        
        return $this->entities[$entityName]->fields;
    }
    
    /**
     * Configuracion de field
     * 
     * Si no existe el field consultado se devuelve una configuracion vacia.
     * No es obligatorio que exista el field en la configuracion, se cargaran los parametros por defecto.
     */
    public function Field(string $entityName, string $fieldName): Field
    {
        $fe = $this->FieldsEntity($entityName);
        return array_key_exists($fieldName, $fe) ? $fe[$fieldName] : new Field();
    }
    
    /**
     * @return string[]
     */
    public function EntityNames(): array
    {
        return array_keys($this->entities);
    }
    
    /**
     * Nombres de campos de la entidad
     * 
     * Importante, por cada entidad y por cada relacion, debe incluirse el campo derivado ID_NAME. 
     * Varios metodos definidos asumen que el valor de _Id esta incluido (EntityVal, DbCache, EntitySql, etc)
     * Utilizar FieldNamesRel, para devolver los nombres de campos junto el nombre de campos de relaciones
     * 
     * @param string $entityName
     * @return string[] Nombres de campos de la entidad
     */
    public function FieldNames(string $entityName): array
    {
        $l = array_keys($this->FieldsEntity($entityName));
        
        // Remove id if exists
        $idIndex = array_search($this->config->idName, $l);
        if ($idIndex !== false) {
            unset($l[$idIndex]);
            $l = array_values($l); // Reindex array
        }
        
        // Insert id at the beginning
        array_unshift($l, $this->config->idName);
        return $l;
    }
    
    /**
     * @param string $entityName
     * @return string[]
     */
    public function FieldNamesWithoutId(string $entityName): array
    {
        $l = array_keys($this->FieldsEntity($entityName));
        
        $idIndex = array_search($this->config->idName, $l);
        if ($idIndex !== false) {
            unset($l[$idIndex]);
            $l = array_values($l); // Reindex array
        }
        
        return $l;
    }
    
    /**
     * Lista de campos de la entidad y sus relaciones de la forma fieldId__fieldName
     * La inclusion del caracter de separacion ayuda a determinar la relacion correspondiente
     * 
     * @param string $entityName
     * @return string[]
     */
    public function FieldNamesAll(string $entityName): array
    {
        return array_merge($this->FieldNames($entityName), $this->FieldNamesRel($entityName));
    }
    
    /**
     * @param string $entityName
     * @return string[]
     */
    public function FieldNamesRel(string $entityName): array
    {

        $fieldNamesR = [];
        
        $entityMetadata = $this->GetEntityMetadata($entityName);

        if (!empty($entityMetadata->relations)) {
            foreach ($entityMetadata->relations as $fieldId => $er) {
                // conviene colocar primero el id para facilitar la division en dapper
                $fieldNamesR[] = $fieldId . $this->config->separator . $this->config->idName;
                
                foreach ($this->FieldNamesWithoutId($er->refEntityName) as $fieldName) {
                    $fieldNamesR[] = $fieldId . $this->config->separator . $fieldName;
                }
            }
        }
        
        return $fieldNamesR;
    }
    
    /**
     * @param string $entityName
     * @return string[]
     */
    public function FieldNamesAdmin(string $entityName): array
    {
        $e = $this->GetEntityMetadata($entityName);
        return array_diff($e->getFieldNames(), $e->noAdmin);
    }
    
    /**
     * @param string $entityName
     * @return EntityMetadata
     * @throws \Exception
     */
    public function GetEntityMetadata(string $entityName): EntityMetadata
    {
        if (!array_key_exists($entityName, $this->entities)) {
            throw new \Exception("La entidad " . $entityName . " no existe");
        }
        
        return $this->entities[$entityName];
    }
    
  
    

    public function CreateDataProvider(): DataProvider
    {
        return new DataProvider($this);
    }

    /**
     * Definir SQL de persistencia
     */
    public abstract function CreateModifyQueries(): ModifyQueries;
    
    /**
     * Definir SQL de consulta
     */
    public abstract function CreateSelectQueries(): SelectQueries;
    
    public function Pf(string $entityName, ?string $fieldId = null): string
    {
        return !empty($fieldId) ? $fieldId . $this->config->separator : "";
    }
    
    public function Pt(string $entityName, ?string $fieldId = null): string
    {
        return !empty($fieldId) ? $fieldId : $this->GetEntityMetadata($entityName)->alias;
    }
    
    public function Map(string $entityName, string $fieldName): string
    {
        return $this->Pt($entityName, $fieldName) . "." . $fieldName;
    }
    
    /**
     * Extrae los elementos de una key
     * 
     * Asegurar existencia de caracter de separación.
     * Se puede controlar por ej.: if (str_contains($key, "__"))
     * 
     * @param string $entityName Nombre de la entidad
     * @param string $key fieldId separator fieldName
     * @return array{fieldId: string, fieldName: string, refEntityName: string} Elementos de la relación
     */
    public function KeyDeconstruction(string $entityName, string $key): array
    {
        $i = strpos($key, $this->config->separator);
        $fieldId = substr($key, 0, $i);
        $refEntityName = $this->GetEntityMetadata($entityName)->relations[$fieldId]->refEntityName;
        $fieldName = substr($key, $i + strlen($this->config->separator));
        
        return [
            'fieldId' => $fieldId,
            'fieldName' => $fieldName,
            'refEntityName' => $refEntityName
        ];
    }

    public function compare(string $entityName, array $dict1, array $dict2, ?CompareParams $cp = null): array
    {
        if (empty($cp)) {
            $cp = new CompareParams();
            $cp->ignoreFields = ["id"];
            $cp->ignoreNonExistent = true;
            $cp->ignoreNull = false;
        }

        $response = [];
        
        if (!empty($cp->ignoreFields)) {
            foreach ($cp->ignoreFields as $key) {
                unset($dict1[$key]);
                unset($dict2[$key]);
            }
        }
        
        if (!empty($cp->fieldsToCompare)) {
            foreach ($this->fieldNames($entityName) as $fieldName) {
                if (!in_array($fieldName, $cp->fieldsToCompare)) {
                    unset($dict1[$fieldName]);
                    unset($dict2[$fieldName]);
                }
            }
        }
        
        foreach ($this->FieldNames($entityName) as $fieldName) {
            if ($cp->ignoreNonExistent && (!array_key_exists($fieldName, $dict1) || !array_key_exists($fieldName, $dict2))) {
                continue;
            }
            
            if ($cp->ignoreNull && (!array_key_exists($fieldName, $dict2) || empty($dict2[$fieldName]))) {
                continue;
            }
            

            if (!array_key_exists($fieldName, $dict1) && array_key_exists($fieldName, $dict2)) {
                $response[$fieldName] = $dict2[$fieldName];
                continue;
            }
            
            if (array_key_exists($fieldName, $dict1) && !array_key_exists($fieldName, $dict2)) {
                $response[$fieldName] = "UNDEFINED";
                continue;
            }
            
            $value1 = $dict1[$fieldName];
            $value2 = $dict2[$fieldName];

            if (empty($value1) && empty($value2)) {
                continue;
            }
            
            if (empty($value1) && !empty($value2)) {
                $response[$fieldName] = $value2;
                continue;
            }
            
            if (!empty($value1) && empty($value2)) {
                $response[$fieldName] = $value2;
                continue;
            }
            
              // Normalize DateTime to ISO format
            if ($value1 instanceof DateTimeInterface) {
                $value1 = $value1->format('Y-m-d H:i:s');
            }
            if ($value2 instanceof DateTimeInterface) {
                $value2 = $value2->format('Y-m-d H:i:s');
            }
            if (strtolower(trim((string)$value1)) !== strtolower(trim((string)$value2))) {
                $response[$fieldName] = $value2;
                continue;
            }
        }
        
        return $response;
    }
}