#!/usr/bin/env php

<?php

    use Doctrine\DBAL\DriverManager;
    use Doctrine\DBAL\Schema\SchemaException;
    use Dotenv\Dotenv;
    use Florian\Abfallkalender\Exceptions\MissingEnvironmentInformation;
use Florian\Abfallkalender\Models\Database\Database;
use Florian\Abfallkalender\Models\Migration\Migration;

    require_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();


    try
    {
        $conn = Database::getConnection();
    } catch (\Doctrine\DBAL\Exception $e)
    {
        echo $e->getMessage();
        die('Verbindung zur Datenbank konnte nicht hergestellt werden');
    }


    $migration = new Migration($conn, $_ENV['PATH_MIGRATION'] ?? '');


    try
    {
        if (isset($_SERVER['argv'][1]))
        {
            switch ($_SERVER['argv'][1])
            {
                case 'migrate':
                    $migration->doMigration();
                    break;
                case 'new':
                    $migration->createMigrationStep();
                    break;
            }
        }
    } catch (SchemaException|MissingEnvironmentInformation|\Doctrine\DBAL\Exception|Exception $e)
    {
        echo "Folgender Fehler ist während der Migrierung aufgetreten: " . $e->getMessage() . PHP_EOL;
    } finally
    {
        $conn->close();
    }

