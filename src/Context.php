<?php
namespace App;


use Fines2\Schema_ as SchemaFines;
use SqlOrganize\Sql\MetadataLoader;
use Pedidos\Schema_ as SchemaPedidos;
use SqlOrganize\Model\Config as ModelConfig;
use SqlOrganize\Sql\Config as SqlConfig;;
use SqlOrganize\Sql\Db;
use SqlOrganizeMy\Sql\DbMy;

class Context
{
    private static ?Db $fines = null;
    private static ?Db $pedidos = null;


    public static function getConfigDbFines(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_FINES;
        $configDb->dbName = DB_NAME_FINES;
        $configDb->user = DB_USER_FINES;
        $configDb->pass = DB_PASS_FINES;
        $configDb->namespace = "Fines2";
        $configDb->dataClassesPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Fines" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;

        return $configDb;
    }

    public static function getConfigModelFines(){
        $configModel = new ModelConfig();
        $configModel->host = DB_HOST_FINES;
        $configModel->dbName = DB_NAME_FINES;
        $configModel->user = DB_USER_FINES;
        $configModel->pass = DB_PASS_FINES;
        $configModel->namespace = "Fines2";
        
        $configModel->schemaClassPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Fines2" . DIRECTORY_SEPARATOR;
        $configModel->dataClassesPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Fines2" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
        $configModel->dbInstance = "\App\Context::getFinesDb()";
        
        return $configModel;
    }

    public static function getConfigDbPedidos(){
        $configDb = new SqlConfig();
        $configDb->host = DB_HOST_PEDIDOS;
        $configDb->dbName = DB_NAME_PEDIDOS;
        $configDb->user = DB_USER_PEDIDOS;
        $configDb->pass = DB_PASS_PEDIDOS;
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
        $configModel->host = DB_HOST_PEDIDOS;
        $configModel->dbName = DB_NAME_PEDIDOS;
        $configModel->user = DB_USER_PEDIDOS;
        $configModel->pass = DB_PASS_PEDIDOS;
        $configModel->namespace = "Pedidos";
        $configModel->schemaClassPath = MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Pedidos" . DIRECTORY_SEPARATOR;
        $configModel->dataClassesPath =  MAIN_PATH . "src" . DIRECTORY_SEPARATOR . "Pedidos" . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR;
        $configModel->dbInstance = "\App\Context::getPedidosDb()";
        $configModel->tablePrefix = 'wpwt_psmsc_';
        return $configModel;
    }




    public static function getFinesDb(): Db {
        $config = self::getConfigDbFines();

        if (self::$fines === null) {
            self::$fines = new DbMy($config);
            self::$fines->entitiesMetadata = SchemaFines::getEntities();
            foreach(self::$fines->entitiesMetadata as $metadata){
                $metadata->db = self::$fines;
                foreach ($metadata->fields as $field) {
                    $field->db = self::$fines;
                }
            }
        }

        return self::$fines;
    }

    public static function getPedidosDb(): Db {
        $config = self::getConfigDbPedidos();

        if (self::$pedidos === null) {
            self::$pedidos = new DbMy($config);
            self::$pedidos->entitiesMetadata = SchemaPedidos::getEntities();
            foreach(self::$pedidos->entitiesMetadata as $metadata){
                $metadata->db = self::$pedidos;
                foreach ($metadata->fields as $field) {
                    $field->db = self::$pedidos;
                }
            }
        }

        return self::$pedidos;

    }
}














