<?php

namespace Fines7\Core;

class Config
{
    private static ?array $config = null;

    public static function finesDb(): array
    {
        return self::get('db.fines', []);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::all();

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public static function all(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $configPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'fines7.php';

        self::$config = self::loadConfigFile($configPath);

        return self::$config;
    }

    private static function loadConfigFile(string $path): array
    {
        if (!is_readable($path)) {
            return [];
        }

        $config = require $path;

        if (!is_array($config)) {
            throw new \RuntimeException("El archivo de configuracion {$path} debe retornar un array.");
        }

        return $config;
    }
}
