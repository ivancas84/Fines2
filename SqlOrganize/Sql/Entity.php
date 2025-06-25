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
class Entity
{
    public string $_entityName = '';

    protected Logging $_logging;

    public Db $_db;

    public string $_label = ""; 

    public function getLogging(){
        if(empty($this->_logging))
            $this->_logging = new Logging();

        return $this->_logging;
    }

    public function getLabel(): string 
    { 
        $entityMetadata = $this->_db->GetEntityMetadata($this->_entityName);
        $ret = "";

        if(empty($this->main)){
            return $this->get($this->_db->config->idName);
        }
        
        foreach($entityMetadata->main as $fieldName)
            $ret .= ValueTypesUtils::toString($this->get($fieldName)) . " ";

        return trim($ret); 
    }
    

    // Status (propiedad opcional para indicar estado)
    // Los estados básicos son:
    //  * 0: Sin guardar.
    //  * 1: Guardada.
    //
    // Se puede extender a varios estados, habitualmente:
    //  * -2 No se sabe si existe o no
    //  * -1 No existe en la base de datos
    //  * 0: Existe en la base de datos pero fue modificado.
    //  * 1: Existe en la base de datos y no fue modificado.
    public int $_status = -1;

    // Índice dentro de una colección
    // Facilita la impresión del número de fila, por ejemplo
    public int $_index = 0;

   
    public static function createById(string $className, mixed $id): Entity {
        $obj = new $className();
        $fetched = $obj->_db->createDataProvider()->fetchEntityByParams($obj->_entityName, ["id" => $id]);

        if (!$fetched) {
            throw new Exception("No record found for ID");
        }

        $fetched->_status = 1;
        return $fetched;
    }

    /**
     * Si se desea llamar a traves de un objeto utilizar $param = get_object_vars($param)
     * Si se desea llamar a traves de una entidad utilizar $entity->toArray();
     */
    public static function createByUnique(string $className, array $param): Entity {
        $obj = new $className;
        $fetched = $obj->_db->createDataProvider()->fetchEntityByUnique($obj->_entityName, $param);

        if ($fetched) {
            $fetched->_status = 1;
            return $fetched;
        } else {
            $obj->ssetFromArray($param);
            $obj->status = -1;
        }

        return $obj;
    }

    public static function createNull(string $className, string $fieldName = "_label", ?string $fieldValue = null)
    {
        $obj = new $className();
        $obj->set("id", null);
        $fieldValue = $fieldValue ?? "-Seleccione " . ucwords($obj->_entityName) . "-";
        $obj->set($fieldName, $fieldValue);
        return $obj;
    }

    public static function createEmpty(string $className, int $status = -1)
    {
        $obj = new $className();
        $obj->_status = $status;
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
    public function set(string $fieldName, $value, $changeStatus = true): void
    {
        if($value == $this->$fieldName)
            return;
        
        $this->$fieldName = $value;
        if($changeStatus && $this->_status > 0)
            $this->_status = 0;
    }

    public function setFk(string $fieldName, Entity $value): void
    {   
        $this->set($fieldName . "_", $value, false);
        $this->set($fieldName, $value->get($this->_db->config->idName));
    }

    /**
     * @todo Falta consultar la entrada relacionada y asignarla a $fieldName . "_"
     */
    public function setFkValue(string $fieldName, $value): void
    {   
        $this->set($fieldName, $value);

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
                $val = ValueTypesUtils::toBool($value);
                $this->set($fieldName, $val);
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
                $className = $refEntityMetadata->getClassNameWithNamespace()."_";
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

   

    public function check(): bool
    {
        $this->getLogging()->clear();
        foreach ($this->_db->fieldNames($this->_entityName) as $fieldName) {
            $this->checkField($fieldName);
        }
        
        return !$this->getLogging()->hasErrors();
    }

    /**
     * Validar valor del field
     */
    public function checkField(string $fieldName): bool
    {
        $this->getLogging()->clearByKey($fieldName);
        
        $field = $this->_db->field($this->_entityName, $fieldName);
        $value = $this->get($fieldName);
        $validation = new Validation($value);
        $validation->clear();
        
        foreach ($field->checks as $checkMethod => $param) {
            switch ($checkMethod) {
                case "type":
                    $validation->type((string)$param);
                    break;
                case "required":
                    if($field->type != "bool") {                    
                        $validation->required();
                    } else if(is_null($value)){
                        $validation->errors[] = ["msg" => "Valor nulo o vacío", "type" => "required"];
                    }
                    break;
            }
        }
        
        foreach ($validation->errors as $error) {
            $this->getLogging()->addErrorLog($fieldName, $error['type'], $error['msg']);
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
            $entity = self::createById($className, $id);
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
     * Comparación de entidades
     */
    public function compare(Entity $entity, CompareParams $cp): array
    {
        return $this->_db->compare($this->_entityName, $this->toArray(), $entity->toArray(), $cp);
    }

    
    public function persist(): mixed
    {
    
        $modifyQueries = $this->_db->CreateModifyQueries();
        $modifyQueries->buildPersistSql($this);
        $modifyQueries->execute();
        return $this->get($this->_db->config->idName);
    }

    public function persistByStatus(): mixed
    {
        if($this->_status == 0) {
            $this->update();
        } elseif($this->_status < 0) {
            $this->insert();
        } 

        return $this->get($this->_db->config->idName);
    }

    public function update(): void
    {
        $modifyQueries = $this->_db->CreateModifyQueries();
        $modifyQueries->buildUpdateSql($this);
        $modifyQueries->execute();
    }

    public function insert(): void
    {
        $modifyQueries = $this->_db->CreateModifyQueries();
        $modifyQueries->buildInsertSql($this);
        $modifyQueries->execute();
    }

    // Métodos auxiliares

  
    

    
}