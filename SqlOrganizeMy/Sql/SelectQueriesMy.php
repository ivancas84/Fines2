<?php

namespace SqlOrganize\Sql;

/**
 * Consultas de selección específicas para SQL Server
 */
class SelectQueriesMy extends SelectQueries
{
    public function __construct(Db $db)
    {
        parent::__construct($db);
    }

    /**
     * Obtiene los nombres de las tablas de la base de datos
     */
    public function getTableNames(): array
    {

        $connection = $this->db->Connection();
        try {
            $sql = "
                SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = :dbName
                ORDER BY TABLE_NAME ASC
            ";
            
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':dbName', DB_NAME_FINES);
            $stmt->execute();
            
            $tables = [];
            while ($row = $stmt->fetch()) {
                $tables[] = $row['TABLE_NAME'];
            }
            
            return $tables;
            
        } finally {
            $connection = null; // Cerrar conexión
        }
    }

    /**
     * Obtiene el siguiente valor de una secuencia
     */
    public function getNextValue(string $entityName, string $fieldName): mixed
    {
        $connection = $this->db->Connection();

        try {
            $sql = "
                SELECT AUTO_INCREMENT 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :tableName
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['tableName' => $entityName]);
            
            return $stmt->fetchColumn();

        } finally {
            $connection = null;
        }
    }

}