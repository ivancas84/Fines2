<?php
namespace App;


use Fines2\Schema_;
use Pedidos\SchemaPedidos;
use SqlOrganize\Model\Config as ModelConfig;
use SqlOrganize\Sql\Config as SqlConfig;;
use SqlOrganize\Sql\Db;
use SqlOrganize\Sql\DbMy;

class Context
{
    private static ?Db $fines = null;
    private static ?Db $pedidos = null;

    public static function getConfigDb(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_FINES2;
        $configDb->dbName = DB_NAME_FINES2;
        $configDb->user = DB_USER_FINES2;
        $configDb->pass = DB_PASS_FINES2;
        $configDb->namespace = "Fines2";
        $configDb->dataClassesPath = MAIN_PATH . "Model" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

    public static function getConfigModel(){
       $configModel = new ModelConfig();
        $configModel->host = DB_HOST_FINES2;
        $configModel->dbName = DB_NAME_FINES2;
        $configModel->user = DB_USER_FINES2;
        $configModel->pass = DB_PASS_FINES2;
        $configModel->namespace = "Fines2";
        $configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\";
        $configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\Model\\";
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
        $configDb->dataClassesPath = MAIN_PATH . "ModelPedidos" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

    public static function getConfigModelPedidos(){
       $configModel = new ModelConfig();
        $configModel->host = DB_HOST_PEDIDOS2;
        $configModel->dbName = DB_NAME_PEDIDOS2;
        $configModel->user = DB_USER_PEDIDOS2;
        $configModel->pass = DB_PASS_PEDIDOS2;
        $configModel->namespace = "Pedidos";
        $configModel->schemaName = 'schema_pedidos';
        $configModel->schemaClassPath = "C:\\xampp\\htdocs\\Fines2\\";
        $configModel->dataClassesPath = "C:\\xampp\\htdocs\\Fines2\\ModelPedidos\\";
        $configModel->dbInstance = "\App\Context::getPedidosDb()";
        return $configModel;
    }



    public static function getFinesDb(): Db {
        if (self::$fines === null) {
            self::initFinesDb();
        }
        return self::$fines;
    }

    public static function getPedidosDb(): Db {
        if (self::$pedidos === null) {
            self::initPedidosDb();
        }
        return self::$pedidos;
    }

    private static function initFinesDb(): void {
        $config = self::getConfigDb();

        if (self::$fines === null) {
            self::$fines = new DbMy($config, Schema_::getEntities());
        }

        foreach(self::$fines->entitiesMetadata as $entityMetadata){
            require_once rtrim($config->dataClassesPath, '/') . '/' . $entityMetadata->getClassName() .'.php';
            require_once rtrim($config->dataClassesPath, '/') . '/' . $entityMetadata->getClassName() .'_.php';
        }
    }

    private static function initPedidosDb(): void {
        $config = self::getConfigDbPedidos();
        if (self::$pedidos === null) {
            self::$pedidos = new DbMy($config, SchemaPedidos::getEntities());
        }

        foreach(self::$pedidos->entitiesMetadata as $entityMetadata){
            require_once rtrim($config->dataClassesPath, '/') . '/' . $entityMetadata->getClassName() .'.php';
            require_once rtrim($config->dataClassesPath, '/') . '/' . $entityMetadata->getClassName() .'_.php';
        }
    }
}














