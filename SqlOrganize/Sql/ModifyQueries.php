<?php

namespace SqlOrganize\Sql;

use PDO;
use Exception;
use InvalidArgumentException;

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


    protected function buildUpdateSqlFromArray($entityName, array $row)
    {
        $prefix = $this->getNextPrefix();

        $sql = $this->generateUpdateSql($entityName, $row, $prefix);

        $idField = $this->db->getEntityMetadata($entityName)->map($this->db->config->id);
        $sql .= sprintf("WHERE %s = :%s%s;\n", $idField, $prefix, $this->db->config->id);

        $this->parameters[$prefix . $this->db->config->id] = $row[$this->db->config->id];
        $this->detail[] = [
            'EntityName' => $entityName,
            'Id' => $row[$this->db->config->id],
            'Action' => 'update'
        ];

        $this->sqlBuilder .= $sql . "\n";
        return $sql;
    }

    protected abstract function generateUpdateSql(string $entityName, array $row, string $prefix): string;

    public function buildInsertSql($data)
    {
        $prefix = $this->getNextPrefix();

        $row = is_object($data) ? $data->toDict() : $data;
        $entityName = is_object($data) ? $data->entityName : $data['entityName'];

        $validFields = $this->db->fieldNamesAdmin($entityName);
        $filteredRow = array_filter($row, function($key) use ($validFields) {
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
            'Id' => is_object($data) ? $data->get($this->db->config->id) : $data[$this->db->config->id],
            'Action' => 'insert'
        ];

        $this->sqlBuilder .= $sql . "\n";
        return $sql;
    }

    public function buildDeleteSqlByIds($entityName, ...$ids)
    {
        $prefix = $this->getNextPrefix();

        $metadata = $this->db->getEntityMetadata($entityName);
        $idField = $this->db->GetEntityMetadata($entityName)->map($this->db->config->id);

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
        return $sql;
    }

    public function execute(PDO $connection, $transaction = null)
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

    public function process(PDO $connection, $transaction = null)
    {
        try {
            $this->execute($connection, $transaction);
            if ($transaction !== null) {
                $transaction->commit();
            }
        } catch (Exception $ex) {
            if ($transaction !== null) {
                $transaction->rollBack();
            }
            throw $ex;
        }
    }

}