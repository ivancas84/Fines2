<?php

namespace SqlOrganize\Sql;

use Override;

/**
 * Contenedor principal para SQL Server
 * 
 * SQL Server agrega espacios en blanco adicionales cuando se utiliza 
 * CONCAT y CONCAT_WS.
 */
class DbMy extends Db
{
    /**
     * Constructor
     * 
     * @param Config $config Configuración
     * @param ISchema $schema Schema
     * @param array|null $cache Cache opcional
     * 
     * @example
     * $connectionString = "server=127.0.0.1;uid=root;pwd=12345;database=test"
     */
    public function __construct(Config $config, ISchema $schema, )
    {
        parent::__construct($config, $schema);
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