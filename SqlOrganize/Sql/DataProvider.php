<?php

namespace SqlOrganize\Sql;

use PDO;

use Exception;

class DataProvider {

    protected Db $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }

    /**
     * Armar árbol de valores a partir de un resultado lineal estructurado de la entidad
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

    private function treeDataToEntities(string $className, array $treeData): array{
        $response = [];
        foreach($treeData as $d) {
            $response[] = $this->treeRowToEntity($className, $d);
        }

        return $response;
    }

    private function treeRowToEntity(string $className, array $treeRow): ?Entity {
        if(empty($treeRow)) return null;

        /**  @var Entity */ $obj = new $className;
        $obj->ssetFromTree($treeRow);
        return $obj;
    }

    protected function _fetchAllByParams(string $sql, string $entityName, array $params = [], array $orderBy = []): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql .= $selectQueries->whereParamsWithOrder($entityName, $params, $orderBy);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchAllJoinByParams(string $entityName, array $params = [], array $orderBy = []): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->selectJoin($entityName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy);
    }

    public function fetchAllByParams(string $entityName, array $params = [], array $orderBy = []): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->select($entityName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy);
    }

    public function fetchAllTreeByParams(string $entityName, array $params = [], array $orderBy): array
    {
        $rawEntities = $this->fetchAllJoinByParams($entityName, $params, $orderBy);

        $response = [];

        // Reorganizar en forma de árbol
        foreach ($rawEntities as &$row) {
            $response[] = $this->valuesTree($entityName, $row);
        }

        return $response; // Ya es array asociativo, no se necesita deserializar
    }

    public function fetchAllEntitiesByParams(string $className, array $params = [], $orderBy = []): array {
        /** @var Entity */ $class = new $className();
        $treeData = $this->fetchAllTreeByParams($class->_entityName, $params, $orderBy);
        return $this->treeDataToEntities($className, $treeData);
    }


    /**
     * Consulta de datos por parámetros únicos
     * Retorna array sin relaciones
     */
    public function fetchByUnique(string $entityName, array $uniqueParams): ?array {
        $selectQueries = $this->db->CreateSelectQueries();
        $sql = $selectQueries->select($entityName);
        $sql .= $selectQueries->whereUnique($entityName, $uniqueParams);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $uniqueParams);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row === false) ? null : $row;
    }

    /**
     * consulta de entidad por parámetros únicos
     * @return  Entity|null Retorna Entity con relaciones
     */
    public function fetchEntityByUnique(string $className, array $uniqueParams): ?Entity {
        /** @var Entity */ $class = new $className;
        $row = $this->fetchByUnique($class->_entityName, $uniqueParams);
        if (empty($row)) return null;
        $treeRow = $this->valuesTree($class->_entityName, $row);
        return $this->treeRowToEntity($className, $treeRow);
    }

    public function fetchEntityByParams(string $className, array $params): ?Entity {
        $entities = $this->fetchAllEntitiesByParams($className, $params);
        if(count($entities)) return $entities[0];
        return null;
    }
    
    /**
     * Fetch data with support for array parameters in SQL queries
     * 
     * @param
     * $params array asociativo
     */
    public function fetchAllSqlByParams(string $sql, ?array $params = null): array
    {
        
        if ($params === null) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        [$processedSql, $processedParams] = $this->db->createSelectQueries()->processArrayParameters($sql, $params);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchSqlByParams(string $sql, ?array $params = null): ?array
    {
        if ($params === null) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        [$processedSql, $processedParams] = $this->db->CreateSelectQueries()->processArrayParameters($sql, $params);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($response === false) ? null : $response;
    }

    /**
     * Consulta de columna
     * 
     * @param $params array asociativo
     */
    public function fetchAllColumnSqlByParams(string $sql, int $columnIndex = 0, ?array $params = null): array
    {
        if ($params === null) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
        }

        [$processedSql, $processedParams] = $this->db->CreateSelectQueries()->processArrayParameters($sql, $params);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
    }

    /**
     * SQL debe consultar el id en la primera columna
     * 
     * La forma mas sencilla de hacer consultas a la base de datos abtrayendo del esquema es mediante este metodo
     * Solo se debe definir un sql que retorne el id de la entidad en la primera columna y se definira un metodo que arme el arbol de entidades.
     * @example
     *   $sql = "
     *       SELECT DISTINCT id 
     *       FROM toma
     *       WHERE curso = :cursos";
     *   $tomas = $dataProvider->fetchAllEntitiesBySqlId("\Fines2\Toma_", $sql, ["cursos"=>$ids_cursos]);
     */
    public function fetchAllEntitiesBySqlId(string $className, string $sql, ?array $params = null): array {
        $ids = $this->fetchAllColumnSqlByParams($sql, 0, $params);
        return $this->fetchAllEntitiesByParams($className, ["id" => $ids]);
    }

    public function fetchEntityBySqlId(string $className, string $sql, ?array $params = null): ?Entity {
        $entities = $this->fetchAllEntitiesBySqlId($className, $sql, $params);
        if(count($entities)) return $entities[0];
        return null;
    }
}
