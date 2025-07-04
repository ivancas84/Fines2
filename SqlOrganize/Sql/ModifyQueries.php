<?php

namespace SqlOrganize\Sql;

use PDO;
use Exception;
use InvalidArgumentException;
use SqlOrganize\Utils\ValueTypesUtils;
use DateTimeInterface;

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

    public function getDetailAction(string $entityName, mixed $id): ?string {
        $count = count($this->detail)-1;
        for($i= $count; $i >= 0; $i--){
            if($this->detail[$i]["EntityName"] == $entityName && $this->detail[$i]["Id"] == $id){
                return $this->detail[$i]["Action"];
            }
        }
        return null;
    }

    public  function htmlDetail(): string {
        $html = "<p><strong>Detalle de persistencia</strong></p>";
        if(!empty($this->detail)){
            $html .= "<pre>";
            $html .= print_r($this->detail, true);
            $html .= "</pre>";
        } else {
            $html .= "<p>No existe persistencia</p>";
        }
        return $html;
    }

    public function toArray(){
        return [
            "sqlBuilder" => $this->sqlBuilder,
            "parameters" => $this->parameters,
            "parameterCounter" => $this->parameterCounter,
            "detail" => $this->detail,
        ];
    }

    public function fromArray(array $data){
        $this->sqlBuilder = $data["sqlBuilder"];
        $this->parameters = $data["parameters"];
        $this->parameterCounter = $data["parameterCounter"];
        $this->detail = $data["detail"];

    }



    /**
     * Generates a unique prefix for parameters to avoid conflicts
     */
    private function getNextPrefix()
    {
        return sprintf("p%d_", $this->parameterCounter++);
    }

    /**
     * Procesa parametros del sql, carga atributos parameters y detail
     */
    private function processArrayParameters($entityName, $action, $sql, $parameters): string{
        [$processedSql, $processedArray] = $this->db->CreateSelectQueries()->processArrayParameters($sql, $parameters);

        foreach ($processedArray as $key => $value) {
            $this->parameters[$key] = $value;
            $this->detail[] = [
                'EntityName' => $entityName,
                'Id' => $value,
                'Action' => $action
            ];
            
        }

        return $processedSql;
    }

    /**
     * Persistencia de entidad, 
     */
    public function buildPersistSql(Entity $data): void
    {
        $id = $this->buildPersistSql_($data->_entityName, $data->toArray());
        $data->set(
            $this->db->config->idName, 
            $id
        );
    }

    /**
     * Persistencia de array, 
     * 
     * @return mixed id persistido
     */
    public function buildPersistSql_($entityName, array $data, ?CompareParams $cp = null): mixed {
        
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if (!empty($existingRow)) {
            $data[$this->db->config->idName] = $existingRow[$this->db->config->idName];

            if (!empty($this->db->compare($entityName, $data, $existingRow, $cp)))
                $this->buildUpdateSql_($entityName, $data);
            
        } else {
            $this->buildInsertSql_($entityName, $data);
        }
        return $data[$this->db->config->idName]; 
    }

    public function buildPersistSqlByStatus(Entity $data): void
    {
        if ($data->_status === 1) // pedido existe y no fue modificado
            return;

        if ($data->_status === 0)
            $this->buildUpdateSql($data);
        else
            $this->buildInsertSql($data);
    }


    public function buildUpdateSql(Entity $data): void
    {
        $this->buildUpdateSql_($data->_entityName, $data->toArray());
    }

    public function buildUpdateSql_(string $entityName, array $data): void
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

    public function buildUpdateSqlByCompare(Entity $entityToUpdate, Entity $entityToCompare, ?CompareParams $cmp = null){
        return $this->buildUpdateSqlByCompare_($entityToUpdate->_entityName, $entityToUpdate->toArray(), $entityToCompare->toArray(), $cmp);
    }

    public function buildUpdateSqlByCompare_(string $entityName, array $dataToUpdate, array $dataToCompare, ?CompareParams $cmp = null): void
    {
        $dataToUpdate[$this->db->config->idName] = $dataToCompare[$this->db->config->idName];

        if (!empty($this->db->compare($entityName, $dataToUpdate, $dataToCompare, $cmp)))
            $this->buildUpdateSql_($entityName, $dataToUpdate);
    }

    /**
     * Construye una consulta SQL UPDATE para actualizar un campo específico usando una entidad
     *
     * @param Entity $entity Entidad con los datos
     * @param string $key Campo a actualizar
     * @return string SQL generado
     */
    public function buildUpdateKeySqlById(Entity $entity, $key): void
    {
        $this->buildUpdateKeyValueSqlById(
            $entity->_entityName, 
            $key, 
            $entity->get($key), 
            $entity->get($this->db->config->idName)
        );
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
    public function buildUpdateKeyValueSqlById($entityName, $key, $value, $id): void
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
    }

    public function buildUpdateKeyValueSqlByIds($entityName, $key, $value, ...$ids): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->map($this->db->config->idName);

        $sql = "UPDATE {$entityMetadata->alias} SET {$key} = :{$prefix}Key " .
               "FROM {$entityMetadata->getSchemaNameAlias()} " .
               "WHERE {$idMap} IN (:{$prefix}Ids)";

        $this->sqlBuilder .= $this->processArrayParameters($entityName, "update", $sql, [$prefix . 'Key' => $value, "{$prefix}Ids" => $ids]);
        $this->sqlBuilder .= ";\n";
    }

    protected function buildUpdateSqlByIds_(string $entityName, array $data, ...$ids): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->Map($this->db->config->idName);
        $sql = $this->generateUpdateSql($entityName, $data, $prefix) . "
            WHERE {$idMap} IN (:{$prefix}Ids)";

        
        $this->sqlBuilder .= $this->processArrayParameters($entityName, "update", $sql, ["{$prefix}Ids" => $ids]);
        $this->sqlBuilder .= ";\n";
    }

    /**
     * Devolver base de SQL para actualizar y cargar parametros en el atributo $parameters
     */
    protected abstract function generateUpdateSql(string $entityName, array $row, string $prefix): string;
    
    public function buildInsertSql(Entity $entity): void{
        $this->buildInsertSql_($entity->_entityName, $entity->toArray());
    }

    public function buildInsertSql_(string $entityName, array $data): void
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
    
    public function buildInsertSqlIfNotExists(Entity $entity): void {
        $entity->sset(
            $this->db->config->idName, 
            $this->buildInsertSqlIfNotExists_($entity->_entityName, $entity->toArray())
        );
    }

    public function buildInsertSqlIfNotExists_(string $entityName, array $data): mixed
    {
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->buildInsertSql_($entityName, $data);
            return $this->detail[count($this->detail)-1]["Id"];
        }

        return $existingRow[$this->db->config->idName];
    }

    public function buildInsertSqlIfNotExistsOrCompare_(string $entityName, array $data, CompareParams $compare){
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->buildInsertSql_($entityName, $data);
        } else {
            $compare = $this->db->compare($entityName, $data, $existingRow, $compare);

            if(!empty($compare))
                throw new Exception("Comparacion diferente " . ValueTypesUtils::toStringDict($compare));
        }
    }


    public function buildDeleteSql(Entity $entity): void {
        $this->buildDeleteSqlById($entity->_entityName, $entity->get($this->db->config->idName));
    }

    public function buildDeleteSqlById($entityName, $id)
    {
        $prefix = $this->getNextPrefix();
        $metadata = $this->db->getEntityMetadata($entityName);
        $idMap = $metadata->map($this->db->config->idName);

        $this->detail[] = [
            'EntityName' => $entityName,
            'Id' => $id,
            'Action' => 'delete'
        ];

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
      
        $sql = sprintf("DELETE %s FROM %s %s WHERE %s IN (:%sIds);\n",
            $metadata->alias,
            $metadata->name,
            $metadata->alias,
            $idField,
            $prefix
        );

        $this->sqlBuilder .= $this->processArrayParameters($entityName, "delete", $sql, ["{$prefix}Ids" => $ids]);
        $this->sqlBuilder .= ";\n";
    }



    /** 
    * @todo el control de DateTimeInterface ex propio de cada motor de base de datos, deeria estar en la subclase, y puede que se defina en otro contexto como por ejemplo al asignar el parametro
    */
    public function _execute(PDO $connection): int
    {
        $sql = $this->sqlBuilder;
        if(empty($sql))
            return 0;   
        
        $stmt = $connection->prepare($sql);

        //en mysql DateTimeInterface requiere que se pase a string, si en los demas motores no hay que hacer lo mismo, se puede pasar a la subclase
        foreach ($this->parameters as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                // Format to a standard datetime string
                $value = $value->format('Y-m-d H:i:s');
            }

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

    /**
    * @todo Todavia no esta implementada la cache
    */
    public function removeCache()
    {
        throw new Exception("Not implemented");
        /*
        if (!empty($this->detail)) {
            $this->db->cache?->remove('queries');
            foreach ($this->detail as $detail) {
                $entityName = $detail['EntityName'] ?? $detail[0];
                $id = $detail['Id'] ?? $detail[1];
                $this->db->cache?->remove($entityName . $id);
            }
        }*/
    }

}