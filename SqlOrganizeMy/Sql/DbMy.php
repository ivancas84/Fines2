<?php

namespace SqlOrganize\Sql;

require_once MAIN_PATH . 'SqlOrganize/Sql/Db.php';
require_once MAIN_PATH . 'SqlOrganize/Sql/DataProvider.php';

require_once MAIN_PATH . 'SqlOrganizeMy/Sql/SelectQueriesMy.php';


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
     * @param ISchema $schema Schema
     * @param array|null $cache Cache opcional
     * 
     * @example
     * $connectionString = "server=127.0.0.1;uid=root;pwd=12345;database=test"
     */
    protected function __construct(Config $config, ISchema $schema)
    {
        parent::__construct($config, $schema);
    }

    protected function createConnection(){
        $this->pdo = new PDO("mysql:host=" . DB_HOST_FINES . ";dbname=" . DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        $this->pdo->exec("SET NAMES 'utf8mb3'");
    }

        // Accessor
    public static function getInstance(?Config $config, ?ISchema $schema): DB
    {
        if (self::$instance === null) {
            if($schema === null || $config === null) {
                throw new \InvalidArgumentException("Schema or Config cannot be null");
            }
            self::$instance = new DbMy($config, $schema);
        }

        return self::$instance;
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