<?php

namespace SqlOrganize\Sql;

use PDO;

use Exception;

class DataProvider {

    protected Db $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }


    public function fetchEntitiesByParams(string $entityName, array $params, string $conn = "AND"): array 
    {
        // Create instance to get table name
        $obj = new $entityName();
        
        // Build the WHERE clause
        $whereClauses = [];
        foreach ($params as $key => $value) {
            $whereClauses[] = "$key = :$key";
        }
        
        $whereClause = implode(" $conn ", $whereClauses);
        
        // Build final SQL query
        $sql = "SELECT DISTINCT id FROM " . $obj->getEntityName();
        
        if (!empty($whereClause)) {
            $sql .= " WHERE " . $whereClause;
        }
        
        return $this->fetchEntitiesBySqlId($entityName, $sql, );
            
    }


    /**
     * SQL debe consultar el id en la primera columna
     * 
     * @example
     *   $sql = "
     *       SELECT DISTINCT id 
     *       FROM toma
     *       WHERE curso = :cursos";
     *   $tomas = $dataProvider->fetchEntitiesByNamedSql("toma", $sql, ["cursos"=>$ids_cursos]);
     */
    public function fetchEntitiesBySqlId(string $entityName, string $sql, ?array $params = null): array {
        $ids = $this->fetchColumnBySql($sql, 0, $params);
        return $this->fetchEntitiesByIds($entityName, ...$ids);
    }

    public function fetchEntitiesByIds(string $entityName, ...$ids): array {
        $treeData = $this->fetchTreeByIds($entityName, ...$ids);
        return $this->treeDataToEntities($entityName, $treeData);
    }

    private function treeDataToEntities(string $entityName, array $treeData){
        $className = $this->db->getEntityMetadata($entityName)->getClassNameWithNamespace();

        $response = [];
        foreach($treeData as $d) {
            $obj = new $className;
            $obj->ssetFromTree($d);
            $response[] = $obj;
        }

        return $response;
    }

    /**
     * Devuelve entidades a partir de ids
     */
    public function fetchTreeByIds(string $entityName, ...$ids): array {
        $rawEntities = $this->fetchDataByIds($entityName, ...$ids);

        // Inicializar relaciones en null
        $fieldNamesRel = $this->db->fieldNamesRel($entityName);

        $response = [];

        // Reorganizar en forma de árbol
        foreach ($rawEntities as &$row) {
            $response[] = $this->valuesTree($entityName, $row);
        }

        return $response; // Ya es array asociativo, no se necesita deserializar
    }

 

/**
 * Procesa parámetros con soporte para arrays, expandiendo arrays a parámetros nombrados
 */
private function processArrayParameters(string $sql, array $params): array
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

/**
 * Consulta de columna
 * 
 * @param $params array asociativo
 */
public function fetchColumnBySql(string $sql, int $columnIndex = 0, ?array $params = null): array
{
    if ($params === null) {
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
    }

    [$processedSql, $processedParams] = $this->processArrayParameters($sql, $params);

    $stmt = $this->db->getPdo()->prepare($processedSql);
    $stmt->execute($processedParams);
    return $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
}

    /**
     * Fetch data with support for array parameters in SQL queries
     * 
     * @param
     * $params array asociativo
     */
    public function fetchDataBySql(string $sql, ?array $params = null): array
    {
        if ($params === null) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        [$processedSql, $processedParams] = $this->processArrayParameters($sql, $params);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function fetchAllEntities(string $entityName): array
    {
        $treeData = $this->fetchAllTree($entityName);
        return $this->treeDataToEntities($entityName, $treeData);
    }

    public function fetchAllTree($entityName){
        $data = $this->fetchAll($entityName);
        $treeData = [];
        foreach($data as $d){
            $treeData[] = $this->valuesTree($entityName, $d);
        }
        return $treeData;
    }
    
    public function fetchAll(string $entityName): array 
    {
        $sql = $this->db->createSelectQueries()->selectAll($entityName);
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Consulta por IDs
     */
    public function fetchDataByIds(string $entityName, ...$ids): array
    {
        $ids = array_unique($ids);
        if (empty($ids)) return [];

        $entityMetadata = $this->db->GetEntityMetadata($entityName);
        $sql = $this->db->createSelectQueries()->selectAll($entityName);
        $sql .= " WHERE " . $entityMetadata->Pt() . "." . $this->db->config->idName . " IN (:ids)";

        [$processedSql, $processedParams] = $this->processArrayParameters($sql, ['ids' => $ids]);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) !== count($ids)) {
            throw new Exception("La consulta no devolvió todos los registros. ¿Están correctos los IDs?");
        }

        return $rows;
    }

    /**
     * Armar árbol de valores
     */
    protected function valuesTree(string $entityName, array $values): array {
        $response = [];

        foreach ($this->db->fieldNames($entityName) as $fieldName) {
            if (array_key_exists($fieldName, $values)) {
                $response[$fieldName] = $values[$fieldName];
            }
        }

        $this->valuesTreeRecursive($values, $this->db->getEntityMetadata($entityName)->tree, $response);

        return $response;
    }

    protected function valuesTreeRecursive(array $values, array $tree, array &$response): void {
        foreach ($tree as $fieldId => $et) {

            $fieldName = $et->fieldName;
            if (isset($response[$fieldName]) && $response[$fieldName] !== null) {
                $response[$fieldName . '_'] = [];

                foreach ($this->db->fieldNames($et->refEntityName) as $refFieldName) {
                    $compositeKey = $fieldId . $this->db->config->separator . $refFieldName;
                    $response[$fieldName . '_'][$refFieldName] = (isset($values[$compositeKey])) ? $values[$compositeKey] : null;
                }

                if (!empty($et->children)) {
                    $this->valuesTreeRecursive($values, $et->children, $response[$fieldName . '_']);
                }
            }
        }
    }
}
