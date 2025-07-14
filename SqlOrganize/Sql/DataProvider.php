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

    private function treeDataToEntities(string $entityName, array $treeData): array{
        $response = [];
        foreach($treeData as $d) {
            $response[] = $this->treeRowToEntity($entityName, $d);
        }

        return $response;
    }

    private function treeRowToEntity(string $entityName, array $treeRow): ?Entity {
        if(empty($treeRow)) return null;

        $className = $this->db->GetEntityMetadata($entityName)->getQualifiedClassName();
        /** @var Entity */ $obj = new $className;
        $obj->ssetFromTree($treeRow);
        return $obj;
    }

    /**
     * @param string $entityName Nombre de la entidad
     * @param array $params Array de parametros a filtrar, deben ser solo columnas de $entityName ["fieldName"=>"value", ...]
     * @param array $orderBy Array de parametros a ordenar, deben ser solo columnas de $entityName, ["fieldName"=>"ASC", ...]
     * @param string $conn Conector entre las condiciones, por defecto "AND"
     */
    protected function _fetchAllByParams(string $sql, string $entityName, array $params = [], array $orderBy = [], $fetchMode = PDO::FETCH_ASSOC): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql .= $selectQueries->whereParamsWithOrder($entityName, $params, $orderBy);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        return $stmt->fetchAll($fetchMode);
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

    public function fetchAllEntitiesByParams(string $entityName, array $params = [], $orderBy = []): array {
        $treeData = $this->fetchAllTreeByParams($entityName, $params, $orderBy);
        return $this->treeDataToEntities($entityName, $treeData);
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
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) > 1) throw new Exception("Consulta por campos unicos retorno más de un resultado");
        return (count($rows) == 0) ? null : $rows[0];
    }

    /**
     * consulta de entidad por parámetros únicos
     * @return  Entity|null Retorna Entity con relaciones
     */
    public function fetchEntityByUnique(string $entityName, array $uniqueParams): ?Entity {
        $row = $this->fetchByUnique($entityName, $uniqueParams);
        if (empty($row)) return null;
        $treeRow = $this->valuesTree($entityName, $row);
        return $this->treeRowToEntity($entityName, $treeRow);
    }

    public function fetchEntityByParams(string $entityName, array $params): ?Entity {
        $entities = $this->fetchAllEntitiesByParams($entityName, $params);
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
     * 
     */
    public function fetchAllColumnByParams($entityName, $fieldName, ?array $params = null, ?array $orderBy = null){
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->selectField($entityName, $fieldName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy, PDO::FETCH_COLUMN);
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
    public function fetchAllEntitiesBySqlId(string $entityName, string $sql, ?array $params = null): array {
        $ids = $this->fetchAllColumnSqlByParams($sql, 0, $params);
        return $this->fetchAllEntitiesByParams($entityName, ["id" => $ids]);
    }

    public function fetchEntityBySqlId(string $entityName, string $sql, ?array $params = null): ?Entity {
        $entities = $this->fetchAllEntitiesBySqlId($entityName, $sql, $params);
        if(count($entities)) return $entities[0];
        return null;
    }
}
