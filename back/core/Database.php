<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;port=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['port'],
                $config['charset']
            );

            self::$pdo = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$pdo;
    }
}
