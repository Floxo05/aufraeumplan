<?php

    use Doctrine\DBAL\DriverManager;
    use Dotenv\Dotenv;
    use Florian\Abfallkalender\Models\Abfallkalender;
use Florian\Abfallkalender\Models\Database\Database;
use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    require_once 'vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    try
    {
        $conn = Database::getConnection();
    } catch (\Doctrine\DBAL\Exception $e)
    {
        echo $e->getMessage();
        die('Verbindung zur Datenbank konnte nicht hergestellt werden');
    }


    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new Twig\Environment($loader);

    $abfallkalender = new Abfallkalender($conn);

    $heutigeAufgaben = $abfallkalender->getAufgaben(date('Y-m-d'));

    try
    {
        echo $twig->render('index.twig', [
            'title' => 'Aufräumplan',
            'heading' => 'Aufräumplan',
            'assets_dir' => __DIR__ . '/assets',
            'aufgaben' => $heutigeAufgaben]);
    } catch (LoaderError|RuntimeError|SyntaxError $e)
    {
        echo $e->getMessage();
        die('Fehler im Rendering der Seite');
    }




