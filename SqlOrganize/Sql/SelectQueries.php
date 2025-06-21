<?php

namespace SqlOrganize\Sql;

use SqlOrganize\Sql\Exceptions\UniqueException;

/**
 * Facilitar la generacion de consultas a la base de datos
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
     * @example
     * $sql = $this->selectJoin($entityName);
     * $sql .= " WHERE " . $this->whereUnique($entityName, $row);
     */
    public function whereUnique($entityName, array $row): string
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

        return " WHERE " . $w;
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

    protected function fieldsSimple($entityName)
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

    protected function from($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        return "FROM " . $metadata->getSchemaName() . " AS " . $metadata->alias . "\n";
    }


    /**
     * @example
     *     $sql = $this->selectJoin($entityName);
     *     $sql .= $this->whereIdsWithOrder($entityName);
     */
    public function whereIdsWithOrder($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);

        $sql = " WHERE " . $metadata->Pt() . "." . $this->db->config->idName . " IN (:ids)";
        $sql .= " ORDER BY FIELD(" . $metadata->Pt() . "." . $this->db->config->idName . ", :ids)";

        return $sql;
    }


    public function maxValue($entityName, $fieldName)
    {
        return "SELECT COALESCE(MAX($" . $fieldName . "), 0) FROM " . $entityName . ";";
    }

    public function select($entityName)
    {
        $sql = "SELECT DISTINCT ";
        $sql .= $this->fieldsSimple($entityName) . "\n";
        $sql .= $this->from($entityName);

        return $sql;
    }

    /**
     * Consulta de relaciones
     * Se asigna un prefijo por cada tabla (ver configuracion en el esquema)
     * 
     * @return string con SQL de relaciones
     */
    public function selectJoin($entityName)
    {
        $sql = "SELECT DISTINCT ";
        $sql .= $this->fields($entityName);
        $sql .= $this->from($entityName);
        $sql .= $this->join($entityName);

        return $sql;
    }

    public function selectId($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        $sql = "SELECT DISTINCT " . $metadata->alias . "." . $this->db->config->idName . "\n";
        $sql .= $this->from($entityName);

        return $sql;
    }

    public function selectIdJoin($entityName)
    {
        $metadata = $this->db->getEntityMetadata($entityName);
        $sql = "SELECT DISTINCT " . $metadata->alias . "." . $this->db->config->idName . "\n";
        $sql .= $this->from($entityName);
        $sql .= $this->join($entityName);

        return $sql;
    }

    /**
     * Definir campos a consultar
     */
    protected function fields($entityName)
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

    public function join($entityName)
    {
        $sql = "";
        if (!empty($this->db->getEntityMetadata($entityName)->tree)) {
            $sql .= $this->joinFk($this->db->getEntityMetadata($entityName)->tree, "", $entityName, true);
        }

        return $sql;
    }

    protected function joinFk($tree, $tableId, $entityName, $checkInner)
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
                $sql .= $this->joinFk($entityTree->children, $fieldId, $entityTree->refEntityName, $checkInner);
            }
        }
        
        return $sql;
    }

    public static function whereAnd($where, $cond)
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

    public function whereParams(string $entityName, array $params = [], string $conn = "AND"): string {
        
        if(empty($params)) {
            return "";
        }

        $metadata = $this->db->getEntityMetadata($entityName);
        
        
        $whereClauses = [];
        foreach ($params as $key => $value) {
            $whereClauses[] = (is_array($value)) ?  $metadata->Pt() . ".$key IN (:$key)" : $metadata->Pt() . ".$key = :$key";
        }
        
        $whereClause = implode(" $conn ", $whereClauses);
        
        return " WHERE " . $whereClause ;
    }

    /**
     * @param string $entityName Nombre de la entidad
     * @param array $params Array de parametros a filtrar, deben ser solo columnas de $entityName
     * @param string $conn Conector entre las condiciones, por defecto "AND"
     */
    public function whereParamsWithOrder($entityName, array $params = [], string $conn = "AND"): string {
        if(empty($params)) {
            return "";
        }

        $metadata = $this->db->getEntityMetadata($entityName);
        $whereClauses = [];

        foreach ($params as $key => $value) {
            $whereClauses[] = (is_array($value)) ?  $metadata->Pt() . ".$key IN (:$key)" : $metadata->Pt() . ".$key = :$key";
        }
        
        $whereClause = implode(" $conn ", $whereClauses);

        reset($params);
        $firstKey = key($params);
        $firstValue = $params[$firstKey];

        $orderByClause = "";
        // Only build ORDER BY FIELD if it's an array with at least two values
        if (is_array($firstValue) && count($firstValue) > 1) {
            $orderByClause = " ORDER BY FIELD(" . $metadata->Pt() . ".$firstKey, :$firstKey)";
        }

        return " WHERE " . $whereClause . $orderByClause;
    }


    /**
     * Auxiliar de whereParamsWithOrder que no toma en cuenta el nombre de la entidad
     */
    public function whereParamsWithOrder_(array $params = [], string $conn = "AND"): string {
        if(empty($params)) {
            return "";
        }
        
        $whereClauses = [];

        foreach ($params as $key => $value) {
            $whereClauses[] = (is_array($value)) ?  "$key IN (:$key)" : "$key = :$key";
        }
        
        $whereClause = implode(" $conn ", $whereClauses);

        reset($params);
        $firstKey = key($params);
        $firstValue = $params[$firstKey];

        $orderByClause = "";
        // Only build ORDER BY FIELD if it's an array with at least two values
        if (is_array($firstValue) && count($firstValue) > 1) {
            $orderByClause = " ORDER BY FIELD($firstKey, :$firstKey)";
        }

        return " WHERE " . $whereClause . $orderByClause;
    }

}