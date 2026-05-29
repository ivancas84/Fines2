<?php

namespace Fines7\Core;

use PDO;

class Database
{
    private static ?PDO $fines = null;

    public static function fines(): PDO
    {
        if (self::$fines instanceof PDO) {
            return self::$fines;
        }

        $config = Config::finesDb();

        if (empty($config['name'])) {
            throw new \RuntimeException('No esta configurado db.fines.name para Fines7.');
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'] ?? 'localhost',
            $config['name'],
            $config['charset'] ?? 'utf8mb3'
        );

        self::$fines = new PDO($dsn, $config['user'] ?? '', $config['pass'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        self::$fines->exec("SET NAMES '" . ($config['charset'] ?? 'utf8mb3') . "'");

        return self::$fines;
    }
}
