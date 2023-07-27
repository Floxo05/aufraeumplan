<?php

namespace Florian\Abfallkalender\Models\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class Database
{

    /**
     * @throws Exception
     */
    public static function getConnection() : Connection
    {
        $connectionParams = [
            'dbname' => $_ENV['DB_NAME'] ?? '',
            'user' => $_ENV['DB_USERNAME'] ?? '',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'host' => $_ENV['DB_HOST'] ?? '',
            'driver' => 'pdo_mysql',
        ];

        return DriverManager::getConnection($connectionParams);
    }
}