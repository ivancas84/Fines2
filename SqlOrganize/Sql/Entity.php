<?php

namespace SqlOrganize\Sql;

use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Logging;
use SqlOrganize\Sql\Field;
use SqlOrganize\Sql\Validation;
use SqlOrganize\Sql\CompareParams;
use SqlOrganize\Utils\ValueTypesUtils;
use Exception;
use DateTime;
use PDO;
use ReflectionClass;
use ReflectionProperty;

/**
 * Comportamiento general para las clases de datos
 */
abstract class Entity
{
    public string $_entityName = '';

    public Logging $_logging;

    public Db $_db;


    public function getLabel(): string 
    { 
        $entityMetadata = $this->_db->GetEntityMetadata($this->_entityName);
        $ret = "";

        foreach($entityMetadata->main as $fieldName)
            $ret .= ValueTypesUtils::toString($this->get($fieldName)) . " ";

        return trim($ret); 
    }
    

    // Status (propiedad opcional para indicar estado)
    // Los estados básicos son:
    //  - 0: Sin guardar.
    //  - 1: Guardada.
    //
    // Se puede extender a varios estados, habitualmente:
    //  - Valores menores a 0: Indican que no existen en la base de datos.
    //  - 0: Existe en la base de datos pero fue modificado.
    //  - 1: Existe en la base de datos y no fue modificado.
    public int $_status = 0;

    // Índice dentro de una colección
    // Facilita la impresión del número de fila, por ejemplo
    public int $index = 0;

    public function __construct()
    {
        $this->_logging = new Logging();
    }

    /**
     * Crear instancia de T a partir del id
     */
    public static function createFromId(string $className, $id)
    {
        $obj = new $className();
        $treeData = $obj->_db->createDataProvider()->fetchTreeByIds($obj->_entityName, $id)[0];
        //TODO setear objeto con el treeData;
        return $obj;
    }

    public static function queryFromId(string $className, $id)
    {
        $obj = new $className();
        $connection = $obj->_db->connection();
        $sql = $obj->_db->createSelectQueries()->byId($obj->_entityName);
        
        $stmt = $connection->prepare($sql);
        $stmt->execute(['Id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $newObj = new $className();
            $newObj->set($result);
            return $newObj;
        }
        return null;
    }

    /**
     * Crear instancia de T utilizando serialización a partir de key > value únicos
     */
    public static function createFromValue(string $className, string $key, $value)
    {
        $obj = new $className();
        $connection = $obj->_db->connection();
        $sql = $obj->_db->createSelectQueries()->byKey($obj->_entityName, $key);
        
        $stmt = $connection->prepare($sql);
        $stmt->execute(['Key' => $value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $newObj = new $className();
            $newObj->set($result);
            return $newObj;
        }
        return null;
    }

    /**
     * Asignar propiedades sin utilizar serialización
     */
    public static function createFromObj(string $className, $source)
    {
        $obj = new $className();
        
        $sourceReflection = new ReflectionClass($source);
        $destReflection = new ReflectionClass($className);
        
        $sourceProperties = $sourceReflection->getProperties();
        $destProperties = $destReflection->getProperties();
        
        foreach ($destProperties as $destProp) {
            $destProp->setAccessible(true);
            
            foreach ($sourceProperties as $sourceProp) {
                $sourceProp->setAccessible(true);
                
                if ($sourceProp->getName() === $destProp->getName()) {
                    $destProp->setValue($obj, $sourceProp->getValue($source));
                    break;
                }
            }
        }
        
        return $obj;
    }

    public static function createNull(string $className, string $fieldName = "label", ?string $fieldValue = null)
    {
        $obj = new $className();
        $obj->set("id", null);
        $fieldValue = $fieldValue ?? "-Seleccione " . ucwords($obj->_entityName) . "-";
        $obj->set($fieldName, $fieldValue);
        return $obj;
    }

    public static function createEmpty(string $className)
    {
        return new $className();
    }

    public static function createEmptyWithStatus(string $className, int $status = -1)
    {
        $obj = new $className();
        $obj->setStatus($status);
        return $obj;
    }

    /**
     * Obtener valor de propiedad
     */
    public function get(string $fieldName)
    {
        return $this->$fieldName;
    }

    /**
     * Asignar valor a propiedad
     */
    public function set(string $fieldName, $value): void
    {
        $this->$fieldName = $value;
    }

    /**
     * Seteo "lento" con conversión de tipos
     */
    public function sset(string $fieldName, $value): void
    {
        if ($value === null || $value === "") {
            $this->set($fieldName, null);
            return;
        }

        $field = $this->_db->Field($this->_entityName, $fieldName);

        switch ($field->type) {
            case "int":
                $this->set($fieldName, (int)$value);
                break;
                
            case "float":
            case "decimal":
                $this->set($fieldName, (float)str_replace('.', ',', (string)$value));
                break;
                
            case "bool":
            case "boolean":
                $this->set($fieldName, ValueTypesUtils::toBool($value));
                break;
                
            case "DateTime":
                if ($value instanceof DateTime) {
                    $this->set($fieldName, $value);
                } else {
                    $this->set($fieldName, new DateTime((string)$value));
                }
                break;
                
            default:
                $this->set($fieldName, $value);
                break;
        }
    }

    /**
     * Asignar valor por defecto a propiedades simples
     */
    public function setDefault(): void
    {
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $default = $this->getDefaultValue($fieldName);
            $this->set($fieldName, $default);
        }
    }

    /**
     * Setear array a propiedades simples
     */
    public function setFromArray(array $data, string $prefix = ""): void
    {
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $key = $prefix . $fieldName;
            if (array_key_exists($key, $data)) {
                $this->set($fieldName, $data[$key]);
            }
        }
    }

    public function ssetFromTree(array $treeData){
        $this->ssetFromArray($treeData);
        $entityMetadata = $this->_db->getEntityMetadata($this->_entityName);
        foreach($entityMetadata->tree as $tree){
            if(array_key_exists($tree->fieldName . "_", $treeData)){
                $refEntityMetadata = $this->_db->getEntityMetadata($tree->refEntityName);
                $className = $this->_db->config->namespace."\\".$refEntityMetadata->getClassName();
                $obj = new $className($this->_db);
                $obj->ssetFromTree($treeData[$tree->fieldName . "_"]);
                $this->set($tree->fieldName . "_", $obj);
            }
        }


    }

    /**
     * Seteo solo de valores no nulos
     */
    public function setNotNull(array $data, string $prefix = ""): void
    {
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $key = $prefix . $fieldName;
            if (array_key_exists($key, $data) && !empty($data[$key])) {
                $this->set($fieldName, $data[$key]);
            }
        }
    }

    /**
     * Seteo "lento" de propiedades simples
     */
    public function ssetFromArray(array $data): void
    {
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            if (array_key_exists($fieldName, $data)) {
                $this->sset($fieldName, $data[$fieldName]);
            }
        }
    }

    /**
     * Seteo lento solo de valores no nulos
     */
    public function ssetNotNull(array $data): void
    {
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            if (array_key_exists($fieldName, $data) && !empty($data[$fieldName])) {
                $this->sset($fieldName, $data[$fieldName]);
            }
        }
    }

    /**
     * Seteo "lento" de un determinado campo, con verificación y conversión de tipo de datos
     */
    public function setDefaultField(string $fieldName): void
    {
        $this->set($fieldName, $this->getDefaultValue($fieldName));
    }

    /**
     * Obtener valor por defecto de un field
     */
    public function getDefaultValue(string $fieldName)
    {
        $field = $this->_db->field($this->_entityName, $fieldName);

        if ($field->defaultValue === null || strpos((string)$field->defaultValue, "?") === 0) {
            return null;
        }

        $defaultStr = strtolower((string)$field->defaultValue);

        switch ($field->type) {
            case "string":
                if (strpos($defaultStr, "guid") !== false) {
                    return ValueTypesUtils::generateGuid();
                }

                if (strpos($defaultStr, "uniqid") !== false) {
                    return uniqid();
                }
                
                if (strpos($defaultStr, "random") !== false) {
                    preg_match('/\((\d+)\)/', (string)$field->defaultValue, $matches);
                    $length = isset($matches[1]) ? (int)$matches[1] : 10;
                    return ValueTypesUtils::randomString($length);
                }
                
                return $field->defaultValue;
                
            case "DateTime":
                if (strpos($defaultStr, "cur") !== false || strpos($defaultStr, "getdate") !== false) {
                    return new DateTime();
                }
                return $field->defaultValue;
                
            case "bool":
            case "boolean":
                return ValueTypesUtils::toBool($field->defaultValue);
                
            case "int":
                return $this->getDefaultInt($field);
                
            default:
                return $field->defaultValue;
        }
    }

    /**
     * Copia superficial
     */
    public function shallowCopy()
    {
        return clone $this;
    }

    /**
     * Resetear valores
     */
    public function reset(): void
    {
        $fieldNames = $this->_db->fieldNames($this->_entityName);
        $idField = $this->_db->config->idName;
        
        // Remover id para procesarlo al final
        $fieldNames = array_filter($fieldNames, function($name) use ($idField) {
            return $name !== $idField;
        });
        
        foreach ($fieldNames as $fieldName) {
            $this->resetField($fieldName);
        }
        
        $this->resetField($idField);
    }

    /**
     * Convertir propiedades simples a array
     */
    public function toArray(): array
    {
        $response = [];
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $response[$fieldName] = $this->get($fieldName);
        }
        return $response;
    }

    /**
     * Resetear un determinado field
     */
    public function resetField(string $fieldName): void
    {
        $field = $this->_db->field($this->_entityName, $fieldName);
        
        foreach ($field->resets as $resetKey => $resetValue) {
            $rk = strtolower($resetKey);
            $val = $this->get($fieldName);
            
            switch ($rk) {
                case "trim":
                    if (!empty($val)) {
                        $this->set($fieldName, trim((string)$val, (string)$resetValue));
                    }
                    break;
                    
                case "removemultiplespaces":
                    if (!empty($val)) {
                        $this->set($fieldName, preg_replace('/\s+/', ' ', (string)$val));
                    }
                    break;
                    
                case "nullifempty":
                    if (empty($val)) {
                        $this->set($fieldName, null);
                    }
                    break;
                    
                case "defaultifnull":
                    if (empty($val)) {
                        $this->set($fieldName, $this->getDefaultValue($fieldName));
                    }
                    break;
                    
                case "nextifnull":
                    if (empty($val)) {
                        $this->setNextValue($fieldName);
                    }
                    break;
                    
                case "setdefault":
                    $this->set($fieldName, $this->getDefaultValue($fieldName));
                    break;
                    
                case "cleandigits":
                    if (!empty($val)) {
                        $this->set($fieldName, preg_replace('/\d/', '', (string)$val));
                    }
                    break;
                    
                case "cleannondigits":
                    if (!empty($val)) {
                        $this->set($fieldName, preg_replace('/\D/', '', (string)$val));
                    }
                    break;
            }
        }
    }

    public function setNextValue(string $fieldName): void
    {
        $value = $this->_db->createSelectQueries()->getNextValue($this->_entityName, $fieldName);
        $this->sset($fieldName, $value);
    }

    /**
     * Obtener valores por defecto para campos enteros
     */
    protected function getDefaultInt(Field $field)
    {
        $defaultStr = strtolower((string)$field->defaultValue);
        
        if (strpos($defaultStr, "next") !== false) {
            return $this->_db->createSelectQueries()->getNextValue($field->entityName, $field->name);
        }
        
        if (strpos($defaultStr, "max") !== false) {
            $connection = $this->_db->getPdo();
            $sql = $this->_db->createSelectQueries()->maxValue($this->_entityName, $field->name);
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn() + 1;
        }
        
        return (int)preg_replace('/\D/', '', (string)$field->defaultValue);
    }

    /**
     * Validación de campos
     */
    protected function validateField(string $fieldName): string
    {
        $field = $this->_db->field($this->_entityName, $fieldName);
        
        if ($field->isRequired()) {
            if ($this->isNullOrEmpty($fieldName)) {
                return "Debe completar valor.";
            }
        }
        
        if ($field->isUnique()) {
            if (!$this->isNullOrEmpty($fieldName)) {
                $connection = $this->_db->getPdo();
                $sql = $this->_db->createSelectQueries()->idKey($this->_entityName, $fieldName);
                $value = $this->get($fieldName);
                
                $stmt = $connection->prepare($sql);
                $stmt->execute(['Key' => $value]);
                $id = $stmt->fetchColumn();
                
                if (!empty($id) && !$this->get($this->_db->config->idName) == $id) {
                    return "Valor existente.";
                }
            }
        }
        
        return "";
    }

    public function check(): bool
    {
        $this->_logging->clear();
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $this->checkField($fieldName);
        }
        
        return !$this->_logging->hasErrors();
    }

    /**
     * Validar valor del field
     */
    public function checkField(string $fieldName): bool
    {
        $this->_logging->clearByKey($fieldName);
        
        $field = $this->_db->field($this->_entityName, $fieldName);
        $validation = new Validation($this->get($fieldName));
        $validation->clear();
        
        foreach ($field->checks as $checkMethod => $param) {
            switch ($checkMethod) {
                case "type":
                    $validation->type((string)$param);
                    break;
                case "required":
                    if (ValueTypesUtils::toBool($param)) {
                        $validation->required();
                    }
                    break;
            }
        }
        
        foreach ($validation->errors as $error) {
            $this->_logging->addErrorLog($fieldName, $error['type'], $error['msg']);
        }
        
        return !$validation->hasErrors();
    }

    /**
     * Recargar valores particulares
     */
    public function reload(): void
    {
        $id = $this->get("id");
        
        if (!empty($id)) {
            $className = get_class($this);
            $entity = self::createFromId($className, $id);
            $this->setFromArray($entity->toArray());
        }
    }

    public function isNullOrEmpty(...$fieldNames): bool
    {
        foreach ($fieldNames as $fieldName) {
            if (empty($this->get($fieldName))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Comparación de arrays
     */
    public function compare(CompareParams $cp): array
    {
        $dict1 = $this->toArray();
        $dict2 = $cp->data;
        $response = [];
        
        if (!empty($cp->ignoreFields)) {
            foreach ($cp->ignoreFields as $key) {
                unset($dict1[$key]);
                unset($dict2[$key]);
            }
        }
        
        if (!empty($cp->fieldsToCompare)) {
            foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
                if (!in_array($fieldName, $cp->fieldsToCompare)) {
                    unset($dict1[$fieldName]);
                    unset($dict2[$fieldName]);
                }
            }
        }
        
        foreach ($this->_db->FieldNames($this->_entityName) as $fieldName) {
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
            
            if (empty($dict1[$fieldName]) && empty($dict2[$fieldName])) {
                continue;
            }
            
            if (empty($dict1[$fieldName]) && !empty($dict2[$fieldName])) {
                $response[$fieldName] = $dict2[$fieldName];
                continue;
            }
            
            if (!empty($dict1[$fieldName]) && empty($dict2[$fieldName])) {
                $response[$fieldName] = $dict2[$fieldName];
                continue;
            }
            
            if (strtolower(trim((string)$dict1[$fieldName])) !== strtolower(trim((string)$dict2[$fieldName]))) {
                $response[$fieldName] = $dict2[$fieldName];
                continue;
            }
        }
        
        return $response;
    }

    
    public function persist()
    {
        /*$connection = $this->_db->connection();
        $modifyQueries = $this->_db->CreateModifyQueries();
        $modifyQueries->buildPersistSql($this);
        $modifyQueries->execute($connection);
        return $this->get($this->_db->config->idName);*/
    }

    public function queryUnique()
    {
        /*$sql = $this->_db->CreateSelectQueries()->unique($this);
        $connection = $this->_db->connection();
        
        $stmt = $connection->prepare($sql);
        $stmt->execute($this->toArray());
        
        return $stmt->fetch(PDO::FETCH_ASSOC);*/
    }

    // Métodos auxiliares

  
    

    
}