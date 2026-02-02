<?php
namespace App;


use Fines2\Schema_ as SchemaFines;
use Pedidos\Schema_ as SchemaPedidos;
use SqlOrganize\Model\Config as ModelConfig;
use SqlOrganize\Sql\Config as SqlConfig;;
use SqlOrganize\Sql\Db;
use SqlOrganizeMy\Sql\DbMy;

class Context
{
    private static ?Db $fines = null;
    private static ?Db $pedidos = null;
    private static bool $finesInitialized = false;
    private static bool $pedidosInitialized = false;


    public static function getConfigDbFines(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_FINES2;
        $configDb->dbName = DB_NAME_FINES2;
        $configDb->user = DB_USER_FINES2;
        $configDb->pass = DB_PASS_FINES2;
        $configDb->namespace = "Fines2";
        $configDb->dataClassesPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Pedidos" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

    public static function getConfigModelFines(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_FINES2;
        $configModel->dbName = DB_NAME_FINES2;
        $configModel->user = DB_USER_FINES2;
        $configModel->pass = DB_PASS_FINES2;
        $configModel->namespace = "Fines2";
        
        $configModel->schemaClassPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Fines2" . DIRECTORY_SEPARATOR;
        $configModel->dataClassesPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Fines2" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
        $configModel->dbInstance = "\App\Context::getFinesDb()";
        
        return $configModel;
    }

    public static function getConfigDbPedidos(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_PEDIDOS2;
        $configDb->dbName = DB_NAME_PEDIDOS2;
        $configDb->user = DB_USER_PEDIDOS2;
        $configDb->pass = DB_PASS_PEDIDOS2;
        $configDb->namespace = "Pedidos";
        $configDb->dataClassesPath = MAIN_PATH . "Pedidos" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
        $configDb->tablePrefix = 'wpwt_psmsc_';
        $configDb->properties = [
            "tickets" => [
                "auth_code" => function() { return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8); }
            ]
        ];
        return $configDb;
    }

    public static function getConfigModelPedidos(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_PEDIDOS2;
        $configModel->dbName = DB_NAME_PEDIDOS2;
        $configModel->user = DB_USER_PEDIDOS2;
        $configModel->pass = DB_PASS_PEDIDOS2;
        $configModel->namespace = "Pedidos";
        $configModel->schemaClassPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Pedidos" . DIRECTORY_SEPARATOR;
        $configModel->dataClassesPath =  MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Pedidos" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
        $configModel->dbInstance = "\App\Context::getPedidosDb()";
        $configModel->tablePrefix = 'wpwt_psmsc_';
        return $configModel;
    }



    public static function getFinesDb(): Db {
        if(!self::$finesInitialized) self::initFinesDb();
        return self::$fines;
    }

    public static function getPedidosDb(): Db {
        if(!self::$pedidosInitialized) self::initPedidosDb();
        return self::$pedidos;
    }

    public static function initFinesDb(): void {
        $config = self::getConfigDbFines();

        if (self::$fines === null) {
            self::$fines = new DbMy($config, SchemaFines::getEntities());
        }
    }

    public static function initPedidosDb(): void {
        $config = self::getConfigDbPedidos();
        if (self::$pedidos === null) {
            self::$pedidos = new DbMy($config, SchemaPedidos::getEntities());
        }

    }
}














