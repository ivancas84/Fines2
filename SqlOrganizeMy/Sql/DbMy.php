<?php

namespace SqlOrganize\Sql;

use PDO;

/**
 * Contenedor principal para SQL Server
 * 
 * SQL Server agrega espacios en blanco adicionales cuando se utiliza 
 * CONCAT y CONCAT_WS.
 */
class DbMy extends Db
{

    private static ?Db $instance = null;

    
    /**
     * Constructor
     * 
     * @param array|null $entities 
     * 
     * @example<
     * $connectionString = "server=127.0.0.1;uid=root;pwd=12345;database=test"
     */
    protected function __construct(Config $config, array $entities)
    {
        parent::__construct($config, $entities);
    }

    protected function initPdo(){
        $this->pdo = new PDO("mysql:host=" . $this->config->host . ";dbname=" . $this->config->dbName, $this->config->user, $this->config->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        $this->pdo->exec("SET NAMES 'utf8mb3'");
    }

        // Accessor
    public static function getInstance(): DB
    {
        return self::$instance;
    }

    public static function initInstance(?Config $config, array $entities){
        self::$instance = new DbMy($config, $entities);

        foreach(self::$instance->entities as $entityMetadata){
            require_once rtrim($config->mainPath, '/') . '/Data/' . $entityMetadata->getClassName() .'.php';
            require_once rtrim($config->mainPath, '/') . '/Data/' . $entityMetadata->getClassName() .'_.php';
        }
    }


    public function CreateSelectQueries(): SelectQueries
    {
        return new SelectQueriesMy($this);
    }

    public function CreateModifyQueries(): ModifyQueries
    {
        return new ModifyQueriesMy($this);
    }

}