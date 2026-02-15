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
        $obj->ssetFromTree($treeRow); //asigna status a 1 y reinicia changelog
        return $obj;
    }

    /**
     * Transformar y ejecutar sql. El SQL debe partir de entityName en el FROM
     * @return array fetchAll
     */
    protected function _fetchAllByParams(string $sql, string $entityName, array $params = [], array $orderBy = [], $fetchMode = PDO::FETCH_ASSOC): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql .= $selectQueries->whereParamsWithOrder($entityName, $params, $orderBy);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $ret = $stmt->fetchAll($fetchMode);
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * Transformar y ejecutar sql. El SQL debe partir de entityName en el FROM
     * @return mixed fetch
     */
    protected function _fetchByParams(string $sql, string $entityName, array $params = [], array $orderBy = [], $fetchMode = PDO::FETCH_ASSOC): mixed
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql .= $selectQueries->whereParamsWithOrder($entityName, $params, $orderBy);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $ret = $stmt->fetch($fetchMode);
        $stmt->closeCursor();
        return $ret;

    }

    /**
     * Generar y ejecutar sql de una entidad y sus relaciones
     * @return array fetchAll
     */
    public function fetchAllJoinByParams(string $entityName, array $params = [], array $orderBy = []): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->selectJoin($entityName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy);
    }

    /**
     * Generar y ejecutar sql de una entidad (no incluye relaciones)
     * @return array fetchAll
     */
    public function fetchAllByParams(string $entityName, array $params = [], array $orderBy = []): array
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->select($entityName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy);
    }

    /**
     * Generar y ejecutar sql para obtener la cantidad de elementos de una entidad
     * @return int fetchColumn
     */
    public function countByParams(string $entityName, array $params = []): int
    {
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->selectCount($entityName);
        $sql .= $selectQueries->whereParams($entityName, $params);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $ret = (int)$stmt->fetchColumn();
        $stmt->closeCursor();
        return $ret;

    }


    /**
     * Generar y ejecutar sql de una entidad y sus relaciones, y tranformarlo en arbol
     * @return array asociativo fetchAll con árbol de relaciones
     */
    public function fetchAllTreeByParams(string $entityName, array $params = [], array $orderBy = []): array
    {
        $rawEntities = $this->fetchAllJoinByParams($entityName, $params, $orderBy);

        $response = [];

        // Reorganizar en forma de árbol
        foreach ($rawEntities as &$row) {
            $response[] = $this->valuesTree($entityName, $row);
        }

        return $response; // Ya es array asociativo, no se necesita deserializar
    }

    /**
     * Generar y ejecutar sql de una entidad y sus relaciones, y devolver la entidad principal y el arbol de propiedades
     * @return array asociativo fetchAll con árbol de entidades
     */
    public function fetchAllEntitiesByParams(string $entityName, array $params = [], array $orderBy = []): array {
        $treeData = $this->fetchAllTreeByParams($entityName, $params, $orderBy);

        return $this->treeDataToEntities($entityName, $treeData);
    }


    /**
     * Generar y ejecutar sql de una entidad sin relaciones utilizando parámetros únicos
     * @return array Datos sin relaciones
     */
    public function fetchByUnique(string $entityName, array $uniqueParams): ?array {
        $selectQueries = $this->db->CreateSelectQueries();
        $sql = $selectQueries->select($entityName);
        $sql .= $selectQueries->whereUnique($entityName, $uniqueParams);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $uniqueParams);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if(count($rows) > 1) throw new Exception("Consulta por campos unicos retorno más de un resultado");
        return (count($rows) == 0) ? null : $rows[0];
    }

    /**
     * Generar y ejecutar sql de una entidad utilizando parámetros únicos
     * @return Entity|null Retorna Entity con relaciones
     */
    public function fetchEntityByUnique(string $entityName, array $uniqueParams): ?Entity {
        $row = $this->fetchByUnique($entityName, $uniqueParams);
        if (empty($row)) return null;
        $treeRow = $this->valuesTree($entityName, $row);
        return $this->treeRowToEntity($entityName, $treeRow);
    }

    /**
     * consulta de entidad por parámetros únicos
     * @return Entity|null Retorna Entity con relaciones
     */

    public function fetchEntityByParams(string $entityName, array $params): ?Entity {
        $entities = $this->fetchAllEntitiesByParams($entityName, $params);
        if(count($entities)) return $entities[0];
        return null;
    }
    
    /**
     * Procesar y ejecutar sql para obtener array asociativo de datos
     * @return array asociativo de datos
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
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * Procesar y ejecutar sql para obtener array asociativo de datos de la primera columna
     * @return array asociativo de datos de la primera columna
     */
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
        $stmt->closeCursor();
        return ($response === false) ? null : $response;
    }

    /**
     * Procesar y ejecutar sql para obtener primer valor de la primera columna
     * @return mixed primer valor de la primera columna
     */
    public function fetchSqlValueByParams(string $sql, ?array $params = null): mixed
    {
        if ($params === null) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_COLUMN, 0) ?: null;
        }

        [$processedSql, $processedParams] = $this->db
            ->CreateSelectQueries()
            ->processArrayParameters($sql, $params);

        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);

        $value = $stmt->fetch(PDO::FETCH_COLUMN, 0);
        $stmt->closeCursor();
        return ($value === false) ? null : $value;
    }

    /**
     * Generar y ejecutar sql para obtener conjunto de valores de un solo fieldName
     * @return array conjunto de valores de un solo fieldName
     */
    public function fetchAllColumnByParams($entityName, $fieldName, array $params = [], array $orderBy = []): array{
        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->selectField($entityName, $fieldName);
        return $this->_fetchAllByParams($sql, $entityName, $params, $orderBy, PDO::FETCH_COLUMN);
    }


    /**
     * Procesar y ejecutar sql para obtener conjunto de valores de una columna indicando indice
     * @return array conjunto de valores de una columna
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
        $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * Regenerar y ejecutar SQL para consultar conjunto de entidades
     * 
     * @param El SQL enviado como parametro debe consultar el id en la primera columna
     * 
     * La forma mas sencilla de hacer consultas a la base de datos abtrayendo del esquema es mediante este metodo
     * Solo se debe definir un sql que retorne el id de la entidad en la primera columna y se definira un metodo que arme el arbol de entidades.
     * 
     * @example
     *   $sql = "
     *       SELECT DISTINCT id 
     *       FROM toma
     *       WHERE curso = :cursos";
     *   $tomas = $dataProvider->fetchAllEntitiesBySqlId("Toma_", $sql, ["cursos"=>$ids_cursos]);
     * 
     * @return array Entity
     */
    public function fetchAllEntitiesBySqlId(string $entityName, string $sql, ?array $params = null): array {
        $ids = $this->fetchAllColumnSqlByParams($sql, 0, $params);
        return $this->fetchAllEntitiesByParams($entityName, ["id" => $ids]);
    }

    /**
     * Regenerar y ejecutar SQL para consultar entidad
     * 
     * @param El SQL enviado como parametro debe consultar el id en la primera columna
     * 
     * La forma mas sencilla de hacer consultas a la base de datos abtrayendo del esquema es mediante este metodo
     * Solo se debe definir un sql que retorne el id de la entidad en la primera columna y se definira un metodo que arme el arbol de entidades.
     * 
     * @example
     *   $sql = "
     *       SELECT DISTINCT id 
     *       FROM toma
     *       WHERE curso = :cursos";
     *   $tomas = $dataProvider->fetchEntityBySqlId("Toma_", $sql, ["cursos"=>$ids_cursos]);
     * 
     * @return array Entity
     */
    public function fetchEntityBySqlId(string $entityName, string $sql, ?array $params = null): ?Entity {
        $entities = $this->fetchAllEntitiesBySqlId($entityName, $sql, $params);
        if(count($entities)) return $entities[0];
        return null;
    }

    function getNextMaxValue($entityName, $fieldName = "id") {
        $table = $this->db->getEntityMetadata($entityName)->getSchemaName();
        $sql = "SELECT IFNULL(MAX({$fieldName}), 0) + 1 AS next_id FROM $table";
        return $this->fetchSqlValueByParams($sql);
    }

    /**
     * Executes a SELECT query associative array  * where the first specified column becomes the key and the second becomes the value.
     * No utiliza relaciones!
     */
    public function fetchPairs(string $entityName, string $keyField, string $valueField, array $params = [], array $orderBy = []): array
    {

        $selectQueries = $this->db->createSelectQueries();
        $sql = $selectQueries->select($entityName);
        $sql .= $selectQueries->whereParamsWithOrder($entityName, $params, $orderBy);
        [$processedSql, $processedParams] = $selectQueries->processArrayParameters($sql, $params);
        $stmt = $this->db->getPdo()->prepare($processedSql);
        $stmt->execute($processedParams);
        $result = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($row[$keyField]) || !array_key_exists($valueField, $row)) {
                throw new Exception("Query must return columns '$keyField' and '$valueField'");
            }
            
            $key = $row[$keyField];
            $result[$key] = $row[$valueField];
        }
        
        $stmt->closeCursor();
        return $result;
    }

  
}
