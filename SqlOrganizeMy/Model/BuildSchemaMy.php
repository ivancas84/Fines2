<?php

namespace SqlOrganize\Model;

use PDO;
use PDOException;
use SqlOrganize\Model\Column;

class BuildSchemaMy extends BuildSchema
{
    private function getPdo(){
         $pdo = new PDO("mysql:host=" . $this->config->host . ";dbname=" . $this->config->dbName, $this->config->user, $this->config->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $pdo->exec("SET NAMES 'utf8mb3'");
            return $pdo;
    }

    protected function getTableNames(): array
    {
        try {
            $pdo = $this->getPdo();

            $sql = "
                SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = :dbName
                ORDER BY TABLE_NAME ASC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dbName', $this->config->dbName);
            $stmt->execute();
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row['TABLE_NAME'];
            }
            
            return $result;
            
        } catch (PDOException $e) {
            throw new \Exception("Error getting table names: " . $e->getMessage());
        }
    }

    protected function getColumns(string $tableName): array
    {
        try {
            $pdo = $this->getPdo();
            
            $sql = "SELECT
    col.TABLE_NAME,
    col.COLUMN_NAME,
    col.COLUMN_DEFAULT,
    CASE WHEN col.IS_NULLABLE = 'YES' THEN 1 ELSE 0 END AS IS_NULLABLE,
    col.DATA_TYPE,
    col.CHARACTER_MAXIMUM_LENGTH,
    col.NUMERIC_PRECISION,
    col.NUMERIC_SCALE,
    CASE WHEN col.COLUMN_TYPE LIKE '%unsigned%' THEN 1 ELSE 0 END AS IS_UNSIGNED,
    CASE
        WHEN col.DATA_TYPE = 'tinyint' AND col.NUMERIC_PRECISION = 3 AND col.COLUMN_TYPE LIKE 'tinyint(1)%'
        THEN 1 ELSE 0
    END AS IS_BOOLEAN,
    MAX(kcu.REFERENCED_TABLE_NAME) AS REFERENCED_TABLE_NAME,
    MAX(kcu.REFERENCED_COLUMN_NAME) AS REFERENCED_COLUMN_NAME,
    MAX(CASE WHEN tc.CONSTRAINT_TYPE = 'PRIMARY KEY' THEN 1 ELSE 0 END) AS IS_PRIMARY_KEY,
    MAX(CASE WHEN tc.CONSTRAINT_TYPE = 'FOREIGN KEY' THEN 1 ELSE 0 END) AS IS_FOREIGN_KEY
FROM information_schema.columns col
LEFT JOIN information_schema.KEY_COLUMN_USAGE kcu
    ON col.TABLE_SCHEMA = kcu.TABLE_SCHEMA
    AND col.TABLE_NAME = kcu.TABLE_NAME
    AND col.COLUMN_NAME = kcu.COLUMN_NAME
LEFT JOIN information_schema.TABLE_CONSTRAINTS tc
    ON tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
    AND tc.TABLE_NAME = kcu.TABLE_NAME
    AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
WHERE col.TABLE_SCHEMA = :dbName
AND col.TABLE_NAME = :tableName
GROUP BY
    col.TABLE_NAME,
    col.COLUMN_NAME,
    col.COLUMN_DEFAULT,
    col.IS_NULLABLE,
    col.DATA_TYPE,
    col.CHARACTER_MAXIMUM_LENGTH,
    col.NUMERIC_PRECISION,
    col.NUMERIC_SCALE,
    col.COLUMN_TYPE
ORDER BY col.COLUMN_NAME;
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dbName', $this->config->dbName);
            $stmt->bindParam(':tableName', $tableName);
            $stmt->execute();
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $column = new Column();
                $column->TABLE_NAME = $row['TABLE_NAME'];
                $column->COLUMN_NAME = $row['COLUMN_NAME'];
                $column->COLUMN_DEFAULT = $row['COLUMN_DEFAULT'];
                $column->IS_NULLABLE = $row['IS_NULLABLE'];
                $column->DATA_TYPE = $row['DATA_TYPE'];
                $column->CHARACTER_MAXIMUM_LENGTH = $row['CHARACTER_MAXIMUM_LENGTH'];
                $column->NUMERIC_PRECISION = $row['NUMERIC_PRECISION'];
                $column->NUMERIC_SCALE = $row['NUMERIC_SCALE'];
                $column->IS_UNSIGNED = $row['IS_UNSIGNED'] ?? 0;
                $column->REFERENCED_TABLE_NAME = $row['REFERENCED_TABLE_NAME'];
                $column->REFERENCED_COLUMN_NAME = $row['REFERENCED_COLUMN_NAME'];
                $column->IS_PRIMARY_KEY = $row['IS_PRIMARY_KEY'];
                $column->IS_FOREIGN_KEY = $row['IS_FOREIGN_KEY'];
                $column->IS_BOOLEAN = $row['IS_BOOLEAN'] ?? 0;

                
                // Para compatibilidad con el método defineField
                $column->MAX_LENGTH = $row['CHARACTER_MAXIMUM_LENGTH'];
                
                $result[] = $column;
            }
            
            return $result;
            
        } catch (PDOException $e) {
            throw new \Exception("Error getting columns for table {$tableName}: " . $e->getMessage());
        }
    }

    protected function getInfoUnique(string $tableName): array
    {
        try {
            $pdo = $this->getPdo();
            
            
            $sql = "
                SELECT 
                    s.INDEX_NAME AS CONSTRAINT_NAME,
                    s.COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.STATISTICS s
                INNER JOIN 
                    INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc 
                    ON s.INDEX_NAME = tc.CONSTRAINT_NAME 
                    AND s.TABLE_SCHEMA = tc.TABLE_SCHEMA
                    AND s.TABLE_NAME = tc.TABLE_NAME
                WHERE 
                    s.TABLE_SCHEMA = :dbName
                    AND s.TABLE_NAME = :tableName
                    AND tc.CONSTRAINT_TYPE IN ('UNIQUE', 'PRIMARY KEY')
                    AND s.NON_UNIQUE = 0
                ORDER BY 
                    s.INDEX_NAME, s.SEQ_IN_INDEX
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dbName', $this->config->dbName);
            $stmt->bindParam(':tableName', $tableName);
            $stmt->execute();
            
            $response = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $constraintName = $row['CONSTRAINT_NAME'];
                $columnName = $row['COLUMN_NAME'];
                
                // Skip if it's the ID column defined in config
                if ($columnName === $this->config->idName) {
                    continue;
                }
                
                if (!isset($response[$constraintName])) {
                    $response[$constraintName] = [];
                }
                
                $response[$constraintName][] = $columnName;
            }
            
            return $response;
            
        } catch (PDOException $e) {
            throw new \Exception("Error getting unique info for table {$tableName}: " . $e->getMessage());
        }
    }
}