<?php

namespace SqlOrganize\Sql;

use PDO;
use Exception;
use InvalidArgumentException;
use SqlOrganize\Utils\ValueTypesUtils;
use DateTimeInterface;

abstract class ModifyQueries
{
    private $sql = "";
    private $parameterCounter = 0;

    public function getCounter(){
        return $this->parameterCounter;
    }

    public function getSql(){
        return $this->sql;
    }
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
            "sql" => $this->sql,
            "parameters" => $this->parameters,
            "parameterCounter" => $this->parameterCounter,
            "detail" => $this->detail,
        ];
    }

    public function fromArray(array $data){
        $this->sql = $data["sql"];
        $this->parameters = $data["parameters"];
        $this->parameterCounter = $data["parameterCounter"];
        $this->detail = $data["detail"];

    }



    /**
     * Generates a unique prefix for parameters to avoid conflicts
     */
    private function getNextPrefix()
    {
        $ret = sprintf("p%d_", $this->parameterCounter++);
        $this->sql .= "# Consulta " . $this->parameterCounter . "\n";
        return $ret;
    }

    /**
     * Procesa parametros del sql y redefine el sql para procesarlo correctamente
     * Carga atributos sql, parameters y detail.
     */
    private function processArrayParameters($entityName, $action, $sql, $parameters): void{
        [$processedSql, $processedArray] = $this->db->CreateSelectQueries()->processArrayParameters($sql, $parameters);

        foreach ($processedArray as $key => $value) {
            $this->parameters[$key] = $value;
            $this->detail[] = [
                'EntityName' => $entityName,
                'Id' => $value,
                'Action' => $action
            ];
            
        }

        $this->sql .= $processedSql;
    }

    /**
     * Persistencia de entidad, 
     */
    public function persistSql(Entity $data): void
    {
        $id = $this->persistSql_($data->_entityName, $data->toArray());
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
    public function persistSql_($entityName, array $data, ?CompareParams $cp = null): mixed {
        
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if (!empty($existingRow)) {
            $data[$this->db->config->idName] = $existingRow[$this->db->config->idName];

            if (!empty($this->db->compare($entityName, $data, $existingRow, $cp)))
                $this->updateSql_($entityName, $data);
            
        } else {
            $this->insertSql_($entityName, $data);
        }
        return $data[$this->db->config->idName]; 
    }

    public function persistSqlByStatus(Entity $data): void
    {
        if ($data->_status === 1) // pedido existe y no fue modificado
            return;

        if ($data->_status === 0)
            $this->updateSql($data);
        else
            $this->insertSql($data);
    }


    public function updateSql(Entity $data): void
    {
        $this->updateSql_($data->_entityName, $data->toArray());
    }

    public function updateSql_(string $entityName, array $data): void
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

        $this->sql .= $sql . "\n";
    }

    public function updateSqlByCompare(Entity $entityToUpdate, Entity $entityToCompare, ?CompareParams $cmp = null){
        return $this->updateSqlByCompare_($entityToUpdate->_entityName, $entityToUpdate->toArray(), $entityToCompare->toArray(), $cmp);
    }

    public function updateSqlByCompare_(string $entityName, array $dataToUpdate, array $dataToCompare, ?CompareParams $cmp = null): void
    {
        $dataToUpdate[$this->db->config->idName] = $dataToCompare[$this->db->config->idName];

        if (!empty($this->db->compare($entityName, $dataToUpdate, $dataToCompare, $cmp)))
            $this->updateSql_($entityName, $dataToUpdate);
    }

    /**
     * Construye una consulta SQL UPDATE para actualizar un campo específico usando una entidad
     *
     * @param Entity $entity Entidad con los datos
     * @param string $key Campo a actualizar
     * @return string SQL generado
     */
    public function updateKeySqlById(Entity $entity, $key): void
    {
        $this->updateKeyValueSqlById(
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
    public function updateKeyValueSqlById($entityName, $key, $value, $id): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->map($this->db->config->idName);
        
        $sql = "UPDATE {$entityMetadata->getSchemaNameAlias()} 
                SET {$key} = :{$prefix}Key 
                WHERE {$idMap} = :{$prefix}Id";
        
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
        $this->sql .= $sql . ";\n";
    }

    public function updateKeyValueSqlByIds($entityName, $key, $value, ...$ids): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->map($this->db->config->idName);

        $sql = "UPDATE {$entityMetadata->alias} SET {$key} = :{$prefix}Key " .
               "FROM {$entityMetadata->getSchemaNameAlias()} " .
               "WHERE {$idMap} IN (:{$prefix}Ids);\n";

        $this->processArrayParameters($entityName, "update", $sql, [$prefix . 'Key' => $value, "{$prefix}Ids" => $ids]);
    }

    protected function updateSqlByIds_(string $entityName, array $data, ...$ids): void
    {
        $prefix = $this->getNextPrefix();
        $entityMetadata = $this->db->getEntityMetadata($entityName);
        $idMap = $entityMetadata->Map($this->db->config->idName);
        $sql = $this->generateUpdateSql($entityName, $data, $prefix) . "
            WHERE {$idMap} IN (:{$prefix}Ids);\n";

        $this->processArrayParameters($entityName, "update", $sql, ["{$prefix}Ids" => $ids]);
    }

    /**
     * Devolver base de SQL para actualizar y cargar parametros en el atributo $parameters
     */
    protected abstract function generateUpdateSql(string $entityName, array $row, string $prefix): string;
    
    public function insertSql(Entity $entity): void{
        $this->insertSql_($entity->_entityName, $entity->toArray());
    }

    public function insertSql_(string $entityName, array $data): void
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

        $this->sql .= $sql . "\n";

        
    }

    public function build(string $sql, array $parameters, ?string $action, ?string $entityName, array $ids = []): void
    {
        $prefix = $this->getNextPrefix();

        // Replace :param with :{prefix}param
        $sql = preg_replace(
            '/:([a-zA-Z_][a-zA-Z0-9_]*)/',
            ':' . $prefix . '$1',
            $sql
        );

        $this->sql .= $sql . "\n";

        foreach ($parameters as $key => $value) {
            $this->parameters[$prefix . $key] = $value;
        }

        if($action !== null && $entityName !== null && count($ids) > 0)
            foreach($ids as $id)
                $this->detail[] = [
                    'EntityName' => $entityName,
                    'Id' => $id,
                    'Action' => $action
                ];
    }


    public function insertSqlIfNotExists(Entity $entity): void {
        $entity->sset(
            $this->db->config->idName, 
            $this->insertSqlIfNotExists_($entity->_entityName, $entity->toArray())
        );
    }

    public function insertSqlIfNotExists_(string $entityName, array $data): mixed
    {
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->insertSql_($entityName, $data);
            return $this->detail[count($this->detail)-1]["Id"];
        }

        return $existingRow[$this->db->config->idName];
    }

    public function insertSqlIfNotExistsOrCompare_(string $entityName, array $data, CompareParams $compare){
        $existingRow = $this->db->CreateDataProvider()->fetchByUnique($entityName, $data);
        if(empty($existingRow)){
            $this->insertSql_($entityName, $data);
        } else {
            $compare = $this->db->compare($entityName, $data, $existingRow, $compare);

            if(!empty($compare))
                throw new Exception("Comparacion diferente " . ValueTypesUtils::toStringDict($compare));
        }
    }


    public function deleteSql(Entity $entity): void {
        $this->deleteSqlById($entity->_entityName, $entity->get($this->db->config->idName));
    }

    public function deleteSqlById($entityName, $id)
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

        $sql = sprintf("DELETE %s FROM %s %s WHERE %s = :%sId;\n",
            $metadata->alias,
            $metadata->name,
            $metadata->alias,
            $idMap,
            $prefix
        );

        $this->sql .= $sql . "\n";
        return $sql;
    }

    public function deleteSqlByIds($entityName, ...$ids): void
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

        $this->processArrayParameters($entityName, "delete", $sql, ["{$prefix}Ids" => $ids]);
    }

    public function migrateRelations($entityName, $fkName, $value_origen, $value_destino){
        /** @var DataProvider */ $dataProvider = $this->db->CreateDataProvider();
        /** @var Entity[] */ $relations = $dataProvider->fetchAllEntitiesByParams($entityName, [$fkName=> $value_origen]);
        foreach($relations as $rel){
            $this->updateKeyValueSqlById(
                $entityName, 
                $fkName, 
                $value_destino,
                $rel->get($this->db->config->idName)
            );
        }
    }


    /** 
    * @todo el control de DateTimeInterface ex propio de cada motor de base de datos, deeria estar en la subclase, y puede que se defina en otro contexto como por ejemplo al asignar el parametro
    */
    public function _execute(PDO $connection): int
    {
        $sql = $this->sql;

        if(empty($sql))
            return 0;   
        
        $stmt = $connection->prepare($sql);

        //en mysql DateTimeInterface requiere que se pase a string, si en los demas motores no hay que hacer lo mismo, se puede pasar a la subclase
        foreach ($this->parameters as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                // Format to a standard datetime string
                $value = $value->format('Y-m-d H:i:s');
            }

            if(is_array($value))
                throw new Exception("Array value detected for key {$key}. Array parameters are not supported in this context.");

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

    public function getSqlPreview(): string
{
    $sql = $this->sql;
    $preview = $sql;

    foreach ($this->parameters as $key => $value) {

        // Handle DateTime
        if ($value instanceof DateTimeInterface) {
            $value = $value->format('Y-m-d H:i:s');
        }

        // NULL → SQL NULL
        if ($value === null) {
            $replacement = "NULL";
        }
        // Booleans → SQL TRUE/FALSE
        elseif (is_bool($value)) {
            $replacement = $value ? 'TRUE' : 'FALSE';
        }
        // Integers → no quotes
        elseif (is_int($value)) {
            $replacement = (string)$value;
        }
        // Floats → no quotes
        elseif (is_float($value)) {
            // Ensure dot decimal separator for SQL
            $replacement = str_replace(',', '.', (string)$value);
        }
        // Strings → quoted + escaped
        elseif (is_string($value)) {
            $escaped = str_replace("'", "''", $value);
            $replacement = "'" . $escaped . "'";
        }
        // Fallback: json encode objects / arrays (optional)
        else {
            throw new Exception("Unsupported parameter type for key {$key}");
        }

        // Replace all occurrences
        $preview = str_replace(':' . $key, $replacement, $preview);
    }

    return $preview;
    }

    //Reiniciar todos los campos menos detail
    public function Reset()
    {
        $this->sql = "";
        $this->parameterCounter = 0;
        $this->parameters = [];
    }

}