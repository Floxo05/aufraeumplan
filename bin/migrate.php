<?php

    use Doctrine\DBAL\DriverManager;
    use Dotenv\Dotenv;
    use Florian\Abfallkalender\Models\Migration\Migration;

    require_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $connectionParams = [
        'dbname' => $_ENV['DB_NAME'] ?? '',
        'user' => $_ENV['DB_USERNAME'] ?? '',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'host' => $_ENV['DB_HOST'] ?? '',
        'driver' => 'pdo_mysql',
    ];

//    var_dump($connectionParams);

    try
    {
        $conn = DriverManager::getConnection($connectionParams);
        var_dump($conn);

//        $conn->connect();
    } catch (\Doctrine\DBAL\Exception $e)
    {
        echo $e->getMessage();
        die('Verbindung zur Datenbank konnte nicht hergestellt werden');
    }

    $migration = new Migration($conn, $_ENV['PATH_MIGRATION'] ?? '');

    $migration->doMigration();


