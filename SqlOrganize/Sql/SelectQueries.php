<?php

namespace SqlOrganize\Sql;

use SqlOrganize\Sql\Exceptions\UniqueException;

/**
 * Selección de datos de una entidad
 * 
 * Los fields se traducen con los metodos de mapeo, deben indicarse con el prefijo. 
 * Ej "($ingreso = %p1) AND (MAX($persona__nombres) = %p1)"
 */
abstract class SelectQueries
{
    public Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }




    /**
     * Definir sql a partir de los campos unicos de una entidad
     * 
     * @param array $row
     * @return string
     * @throws UniqueException
     */
    public function unique($entityName, array $row)
    {
        $metadata = $this->db->getEntityMetadata($entityName);

        if (empty($row)) {
            throw new UniqueException("El parametro de condicion unica esta vacio");
        }

        $whereUniqueList = [];
        foreach ($metadata->unique as $fieldName) {
            foreach ($row as $key => $value) {
                $k = str_replace('$', '', $key);

                if ($k == $fieldName) {
                    if ($value === null) {
                        continue; // por el momento se ignoran los campos unicos nulos!!!
                        $whereUniqueList[] = $k . " IS NULL";
                        break;
                    }
                    $whereUniqueList[] = $k . " = :" . $k;
                    break;
                }
            }
        }

        $w = "";
        if (count($whereUniqueList) > 0) {
            $w = "(" . implode(") OR (", $whereUniqueList) . ")";
        }

        $ww = "";
        foreach ($metadata->uniqueMultiple as $um) {
            $ww = $this->uniqueMultiple($um, $row);
            if (!empty($ww)) {
                $w .= empty($w) ? $ww : " OR " . $ww;
            }
        }

        $ww = $this->uniqueMultiple($metadata->pk, $row);
        if (!empty($ww)) {
            $w .= empty($w) ? $ww : " OR " . $ww;
        }

        if (empty($w)) {
            throw new UniqueException("No se pudo definir condicion de campo unico con el parametro indicado");
        }

        $sql = "SELECT DISTINCT ";
        $sql .= $this->sqlFieldsSimple($entityName) . " 
";
        $sql .= $this->sqlFrom($entityName);
        $sql .= " WHERE " . $w;

        return $sql;
    }

    protected function uniqueMultiple($fields, array $param)
    {
        if (empty($fields)) {
            return "";
        }

        $existsUniqueMultiple = true;
        $whereMultipleList = [];
        
        foreach ($fields as $field) {
            if (!$existsUniqueMultiple) {
                break;
            }

            $existsUniqueMultiple = false;

            foreach ($param as $key => $value) {
                $k = str_replace('$', '', $key);
                if ($k == $field) {
                    $existsUniqueMultiple = true;
                    if ($value === null) {
                        $whereMultipleList[] = $k . " IS NULL";
                        break;
                    }
                    $whereMultipleList[] = $k . " = :" . $k;
                    break;
                }
            }
        }

        if ($existsUniqueMultiple && count($whereMultipleList) > 0) {
            return "(" . implode(") AND (", $whereMultipleList) . ")";
        }

        return "";
    }

    protected function sqlFieldsSimple($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        $sql = "";

        $sql .= $metadata->map($this->db->config->idName) . ", ";
        foreach ($metadata->getFieldNames() as $fieldName) {
            if($fieldName == $metadata->map($this->db->config->idName))
                continue;
            $sql .= $metadata->map($fieldName) . ", ";
        }
        
        return rtrim($sql, ', ');
    }

    protected function sqlFrom($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        return "FROM " . $metadata->getSchemaName() . " AS " . $metadata->alias . "\n";
    }

    public function byId($entityName)
    {
        $sql = "SELECT DISTINCT ";
        $sql .= $this->sqlFieldsSimple($entityName);
        $sql .= $this->sqlFrom($entityName);
        $sql .= " WHERE " . $this->db->config->idName . " = :Id";

        return $sql;
    }

    public function byIds($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);

        $sql = "SELECT DISTINCT ";
        $sql .= $this->sqlFieldsSimple($entityName);
        $sql .= $this->sqlFrom($entityName);
        $sql .= " WHERE " . $metadata->alias . "." . $this->db->config->idName . " IN (:Ids)";

        return $sql;
    }

    public function byKey($entityName, $key)
    {
        $sql = "SELECT DISTINCT ";
        $sql .= $this->sqlFieldsSimple($entityName);
        $sql .= $this->sqlFrom($entityName);
        $sql .= " WHERE " . $key . " = :Key";

        return $sql;
    }

    /**
     * Existencia de key = value
     * Utilizar PDO para ejecutar como booleano
     */
    public function existsKey($entityName, $key)
    {
        $sql = "SELECT 1 ";
        $sql .= $this->sqlFrom($entityName);
        $sql .= " WHERE " . $key . " = :Key";

        return $sql;
    }

    /**
     * Existencia de key = value
     * Utilizar para obtener ID o null
     */
    public function idKey($entityName, $key)
    {
        return "SELECT id FROM " . $entityName . " WHERE " . $key . " = :Key;";
    }

    public function maxValue($entityName, $fieldName)
    {
        return "SELECT COALESCE(MAX($" . $fieldName . "), 0) FROM " . $entityName . ";";
    }

    /**
     * Consulta de relaciones
     * Se asigna un prefijo por cada tabla (ver configuracion en el esquema)
     * 
     * @return string con SQL de relaciones
     */
    public function selectAll($entityName)
    {
        $sql = "SELECT DISTINCT ";
        $sql .= $this->sqlFields($entityName);
        $sql .= $this->sqlFrom($entityName);
        $sql .= $this->sqlJoin($entityName);

        return $sql;
    }

    public function selectId($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        $sql = "SELECT DISTINCT " . $metadata->alias . "." . $this->db->config->idName . "\n";
        $sql .= $this->sqlFrom($entityName);
        $sql .= $this->sqlJoin($entityName);

        return $sql;
    }

    /**
     * Definir campos a consultar
     */
    protected function sqlFields($entityName)
    {
        $fields = $this->db->fieldNamesAll($entityName);
        $sql = "";
        $entityMetadata = $this->db->getEntityMetadata($entityName);

        foreach ($fields as $fieldName) {
            if (strpos($fieldName, $this->db->config->separator) !== false) {
                $ff = explode($this->db->config->separator, $fieldName);
                $refEntityName = $entityMetadata->relations[$ff[0]]->refEntityName;
                $sql .= $this->db->getEntityMetadata($refEntityName)->map($ff[1], $ff[0]) . " AS '" . $fieldName . "', ";
            } else {
                $sql .= $entityMetadata->map($fieldName) . ", ";
            }
        }
        
        $sql = rtrim($sql, ', ');
        return $sql . "\n";
    }

    protected function traduceFieldsAs($entityName, $_sql)
    {
        if (empty($_sql)) {
            return "";
        }

        $fields = explode(',', $_sql);
        $sql = "";

        $entityMetadata = $this->db->getEntityMetadata($entityName);
        foreach ($fields as $fieldName) {
            $fieldName = trim($fieldName);
            if (strpos($fieldName, $this->db->config->separator) !== false) {
                $ff = explode($this->db->config->separator, $fieldName);
                $sql .= $this->db->getEntityMetadata($entityMetadata->relations[$ff[0]]->refEntityName)->map($ff[1], $ff[0]) . " AS '" . $fieldName . "', ";
            } else {
                $sql .= $entityMetadata->map($fieldName) . " AS '" . $fieldName . "', ";
            }
        }
        
        return rtrim($sql, ', ');
    }

    public function sqlJoin($entityName)
    {
        $sql = "";
        if (!empty($this->db->getEntityMetadata($entityName)->tree)) {
            $sql .= $this->sqlJoinFk($this->db->getEntityMetadata($entityName)->tree, "", $entityName, true);
        }

        return $sql;
    }

    protected function sqlJoinFk($tree, $tableId, $entityName, $checkInner)
    {
        if (empty($tableId)) {
            $tableId = $this->db->getEntityMetadata($entityName)->alias;
        }

        $sql = "";
        foreach ($tree as $fieldId => $entityTree) {
            $schemaName = $this->db->getEntityMetadata($entityTree->refEntityName)->getSchemaName();
            $field = $this->db->field($entityName, $entityTree->fieldName);
            
            $join = "";
            if ($field->isRequired() && $checkInner) {
                $join = "INNER";
            } else {
                $join = "LEFT OUTER";
                $checkInner = false;
            }

            $sql .= $join . " JOIN " . $schemaName . " AS " . $fieldId . " ON (" . $tableId . "." . $entityTree->fieldName . " = " . $fieldId . "." . $entityTree->refFieldName . ")\n";

            if (!empty($entityTree->children)) {
                $sql .= $this->sqlJoinFk($entityTree->children, $fieldId, $entityTree->refEntityName, $checkInner);
            }
        }
        
        return $sql;
    }

    public static function andCondition($where, $cond)
    {
        if (empty($where)) {
            $where = " WHERE " . $cond;
        } else {
            $where .= " AND (" . $cond . ")";
        }

        return $where;
    }

    // Abstract methods that must be implemented by subclasses
    
    /**
     * Get table names - each engine should implement this differently
     */
    abstract public function getTableNames();

    /**
     * Get next value - each engine should implement this differently
     * @todo no deberia estar en DataProvider?
     */
    abstract public function getNextValue(string $entityName, string $fieldName): mixed;

    public function params($entityName, array $params, string $conn = "AND"): array {
        
        $className = $this->db->getEntityMetadata($entityName)->getClassNameWithNamespace() . "_";
        $obj = new $className;
        
        // Build the WHERE clause
        $whereClauses = [];
        foreach ($params as $key => $value) {
            $whereClauses[] = (is_array($value)) ?  "$key IN (:$key)" : "$key = :$key";
        }
        
        $whereClause = implode(" $conn ", $whereClauses);
        
        // Build final SQL query
        $sql = "SELECT DISTINCT id FROM " . $obj->_entityName;
        
        if (!empty($whereClause)) {
            $sql .= " WHERE " . $whereClause;
        }

        return $this->processArrayParameters($sql, $params);
    }

    /**
     * Procesa parámetros con soporte para arrays, expandiendo arrays a parámetros nombrados
     * Si por ejemplo como parametro recibo ["ids" => ["1", "2", "3"]] entonces genera un nuevo array de parametros ["ids0" = "1", "ids1" = "2", "ids2" = "3"]
     * Y en el sql reemplaza el string ":ids" por ":id0, :id1, :id2".
     */
    public function processArrayParameters(string $sql, array $params): array
    {
        $processedParams = [];
        $processedSql = $sql;

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    // Array vacío - reemplazar con condición siempre falsa
                    $processedSql = str_replace(":$key", 'NULL', $processedSql);
                } else {
                    // Crear parámetros nombrados para cada elemento
                    $namedParams = [];
                    foreach ($value as $i => $arrayValue) {
                        $paramName = "{$key}_{$i}";
                        $namedParams[] = ":$paramName";
                        $processedParams[$paramName] = $arrayValue;
                    }
                    $processedSql = str_replace(":$key", implode(',', $namedParams), $processedSql);
                }
            } else {
                $processedParams[$key] = $value;
            }
        }

        return [$processedSql, $processedParams];
    }
}