<?php

namespace SqlOrganize\Sql;

use PDO;
use Exception;
use InvalidArgumentException;
use SqlOrganize\Utils\ValueTypesUtils;

abstract class ModifyQueries
{
    private $sqlBuilder = "";
    private $parameterCounter = 0;

    /**
     * Detalle de elementos persistidos.
     * Permite identificar rápidamente todas las entidades modificadas en la base de datos.
     * 
     * @var array Array of arrays with keys: EntityName, Id, Action
     */
    public $detail = [];

    /**
     * Parámetros dinámicos opcionales para consultas múltiples.
     * 
     * @var array
     */
    public $parameters = [];

    public Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db ?? throw new InvalidArgumentException('Database instance cannot be null');
    }

    /**
     * Generates a unique prefix for parameters to avoid conflicts
     */
    private function getNextPrefix()
    {
        return sprintf("p%d_", $this->parameterCounter++);
    }

    public function buildPersistSql(Entity $data)
    {
        $this->buildPersistSqlFromArray($data->_entityName, $data->toArray());
    }

    public function buildPersistSqlFromArray($entityName, array $data){
        $existingRow = $this->db->CreateDataProvider()->fetchDataByUnique($entityName, $data);

        if (!empty($existingRow)) {
            $data[$this->db->config->idName] = $existingRow[$this->db->config->idName];

            $compareParams = new CompareParams();
            $compareParams->ignoreNonExistent = true;
            $compareParams->ignoreNull = false;
            $compareParams->data = $existingRow;

            if (!empty($this->db->compare($entityName, $data, $compareParams))) {
                return $this->buildUpdateSqlFromArray($entityName, $data);
            }

            return; // registro idéntico
        }

        return $this->buildInsertSqlFromArray($entityName, $data);
    }


    
    protected abstract function generateUpdateSql(string $entityName, array $row, string $prefix): string;

    public function buildPersistSqlByStatus(Entity $data)
    {
        if ($data->_status === 1) // pedido existe y no fue modificado
            return;

        if ($data->_status > -1)
            $this->buildUpdateSql($data);
        else
            $this->buildInsertSql($data);
    }


    protected function buildUpdateSqlFromArray(string $entityName, array $data): void
    {
        $prefix = $this->getNextPrefix();

        $sql = $this->generateUpdateSql($entityName, $data, $prefix);

        $idField = $this->db->getEntityMetadata($entityName)->map($this->db->config->idName);
        $sql .= sprintf("WHERE %s = :%s%s;\n", $idField, $prefix, $this->db->config->idName);

        $this->parameters[$prefix . $this->db->config->idName] = $data[$this->db->config->idName];
        $this->detail[] = [
            'EntityName' => $entityName,
            'Id' => $data[$this->db->config->idName],
            'Action' => 'update'
        ];

        $this->sqlBuilder .= $sql . "\n";
    }

    //@todo se puede definir una sola consulta con IN?
    protected function buildUpdateSqlFromArrayByIds(string $entityName, array $data, ...$ids): void
    {
        foreach ($ids as $id) {
            $data[$this->db->config->idName] = $id;
            $this->buildUpdateSqlFromArray($entityName, $id);
        }
    }

    public function buildUpdateSqlByCompare(Entity $entityToUpdate, Entity $entityToCompare){
        return $this->buildUpdateSqlFromArrayByCompare($entityToUpdate->_entityName, $entityToUpdate->toArray(), $entityToCompare->toArray());
    }

    public function buildUpdateSqlFromArrayByCompare(string $entityName, array $dataToUpdate, array $dataToCompare)
    {
        $dataToUpdate[$this->db->config->idName] = $dataToCompare[$this->db->config->idName];

        $cmp = new CompareParams();
        $cmp->ignoreNonExistent = true;
        $cmp->ignoreNull = false;
        $cmp->data = $dataToCompare;

        if (!empty($this->db->compare($entityName, $dataToUpdate, $cmp)))
            return $this->buildUpdateSqlFromArray($entityName, $dataToUpdate);

        return ''; // registro idéntico
    }

     /**
     * Construye una consulta SQL UPDATE para actualizar un campo específico por ID
     *
     * @param string $entityName Nombre de la entidad
     * @param string $key Campo a actualizar
     * @param mixed $value Nuevo valor
     * @param mixed $id ID del registro
     * @return string SQL generado
     */
    public function buildUpdateKeyValueSqlById($entityName, $key, $value, $id) 
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->map($this->db->config->idName);
        
        $sql = "UPDATE {$entityMetadata->alias} SET {$key} = :{$prefix}Key " .
               "FROM {$entityMetadata->getSchemaNameAlias()} " .
               "WHERE {$idMap} = :{$prefix}Id";
        
        // Almacenar parámetros
        $this->parameters[$prefix . 'Key'] = $value;
        $this->parameters[$prefix . 'Id'] = $id;
        
        // Agregar al detalle
        
        $this->detail[] = [
            'EntityName' => $entityName,
            'Id' => $id,
            'Action' => 'update'
        ];
        
        // Agregar al SQL builder
        $this->sqlBuilder .= $sql . ";\n";
        
        return $sql;
    }

       /**
     * Construye una consulta SQL UPDATE para actualizar un campo específico usando una entidad
     *
     * @param Entity $entity Entidad con los datos
     * @param string $key Campo a actualizar
     * @return string SQL generado
     */
    public function buildUpdateKeySqlById(Entity $entity, $key) 
    {
        return $this->buildUpdateKeyValueSqlById(
            $entity->_entityName, 
            $key, 
            $entity->get($key), 
            $entity->get($this->db->config->idName)
        );
    }
    
    public function buildUpdateKeyValueSqlByIds($entityName, $key, $value, ...$ids): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->map($this->db->config->idName);

        $sql = "UPDATE {$entityMetadata->alias} SET {$key} = :{$prefix}Key " .
               "FROM {$entityMetadata->getSchemaNameAlias()} " .
               "WHERE {$idMap} IN (:{$prefix}Ids)";

        foreach ($ids as $id) {
            $this->detail[] = [
                'EntityName' => $entityName,
                'Id' => $id,
                'Action' => 'update'
            ];
        }

        $this->parameters[$prefix . 'Key'] = $value;
        $this->parameters[$prefix . 'Ids'] = $ids;

        $this->sqlBuilder .= $sql . ";\n";
    }

    public function buildInsertSql(Entity $entity): void{
        $this->buildInsertSqlFromArray($entity->_entityName, $entity->toArray());
    }

    public function buildInsertSqlFromArray(string $entityName, array $data): void
    {
        $prefix = $this->getNextPrefix();

        $validFields = $this->db->fieldNamesAdmin($entityName);
        $filteredRow = array_filter($data, function($key) use ($validFields) {
            return in_array($key, $validFields);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($filteredRow as $key => $value) {
            $this->parameters[$prefix . $key] = $value;
        }

        $schemaName = $this->db->getEntityMetadata($entityName)->getSchemaName();
        $sql = sprintf("INSERT INTO %s (%s)\n", $schemaName, implode(", ", array_keys($filteredRow)));
        $sql .= sprintf("VALUES (%s);\n", implode(", ", array_map(function($k) use ($prefix) {
            return ":" . $prefix . $k;
        }, array_keys($filteredRow))));

        $this->detail[] = [
            'EntityName' => $entityName,
            'Id' => $data[$this->db->config->idName],
            'Action' => 'insert'
        ];

        $this->sqlBuilder .= $sql . "\n";
    }

    public function buildInsertSqlIfNotExists(Entity $entity): object{
        return $this->buildInsertSqlFromArrayIfNotExists($entity->_entityName, $entity->toArray());
    }

    public function buildInsertSqlFromArrayIfNotExists(string $entityName, array $data): object
    {
        $existingRow = $this->db->CreateDataProvider()->fetchDataByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->buildInsertSqlFromArray($entityName, $data);
            return $this->detail[count($this->detail)-1]["Id"];
        }

        return $existingRow[$this->db->config->idName];
    }

    public function buildInsertSqlFromArrayIfNotExistsExceptionByCompare(string $entityName, array $data, CompareParams $compare){
        $existingRow = $this->db->CreateDataProvider()->fetchDataByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->buildInsertSqlFromArray($entityName, $data);
        } else {
            $compare->data = $existingRow;
            $compare = $this->db->compare($entityName, $data, $compare);

            if(!empty($compare))
                throw new Exception("Comparacion diferente " . ValueTypesUtils::toStringDict($compare));
        }
    }

    public function buildUpdateSql(Entity $data)
    {
        $this->buildUpdateSqlFromArray($data->_entityName, $data->toArray());
    }

    public function buildDeleteSql(Entity $entity): void {
        $this->buildDeleteSqlById($entity->_entityName, $entity->get($this->db->config->idName));
    }

    public function buildDeleteSqlById($entityName, $id = null)
    {
        $prefix = $this->getNextPrefix();
        $metadata = $this->db->getEntityMetadata($entityName);
        $idMap = $metadata->map($this->db->config->idName);

        if (!empty($id)){
            $this->detail[] = [
                'EntityName' => $entityName,
                'Id' => $id,
                'Action' => 'delete'
            ];
        }

        $this->parameters[$prefix . 'Id'] = $id;

        $sql = sprintf("DELETE %s FROM %s %s WHERE %s = (:%sId);\n",
            $metadata->alias,
            $metadata->name,
            $metadata->alias,
            $idMap,
            $prefix
        );

        $this->sqlBuilder .= $sql . "\n";
        return $sql;
    }

    public function buildDeleteSqlByIds($entityName, ...$ids): void
    {
        $prefix = $this->getNextPrefix();

        $metadata = $this->db->getEntityMetadata($entityName);
        $idField = $metadata->map($this->db->config->idName);

        foreach ($ids as $id) {
            $this->detail[] = [
                'EntityName' => $entityName,
                'Id' => $id,
                'Action' => 'delete'
            ];
        }

        $this->parameters[$prefix . "Ids"] = $ids;

        $sql = sprintf("DELETE %s FROM %s %s WHERE %s IN (:%sIds);\n",
            $metadata->alias,
            $metadata->name,
            $metadata->alias,
            $idField,
            $prefix
        );

        $this->sqlBuilder .= $sql . "\n";
    }

    public function _execute(PDO $connection)
    {
        $sql = $this->sqlBuilder;
        
        $stmt = $connection->prepare($sql);
        foreach ($this->parameters as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $result = $stmt->execute();
        if(!$result) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("SQL Error: " . $errorInfo[2]);
        }
        return $stmt->rowCount();
    }

    public function execute()
    {
            $pdo = $this->db->getPdo();
            $this->_execute($pdo);
    }

    public function process()
    {
        try {
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();
            $this->_execute($pdo);
            $pdo->commit();
        } catch (Exception $ex) {
            $pdo->rollBack();
            throw $ex;
        }
    }

    public function removeCache()
    {
        /*if (!empty($this->detail)) {
            $this->db->cache?->remove('queries');
            foreach ($this->detail as $detail) {
                $entityName = $detail['EntityName'] ?? $detail[0];
                $id = $detail['Id'] ?? $detail[1];
                $this->db->cache?->remove($entityName . $id);
            }
        }*/
    }

}