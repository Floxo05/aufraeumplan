<?php

    use Dotenv\Dotenv;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    require_once 'vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $connectionParams = [
        'dbname' => $_ENV['DB_NAME'] ?? '',
        'user' => $_ENV['DB_USERNAME'] ?? '',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'host' => $_ENV['DB_HOST'] ?? '',
        'driver' => 'pdo_mysql',
    ];
try {
    $pdo = new PDO('mysql:dbname=aufraeumplan;host=localhost', 'fwolf', 'florian');
} catch (Exception $e)
{
    echo $e->getMessage();
}


    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new Twig\Environment($loader);

    try
    {
        echo $twig->render('index.twig', ['title' => 'Aufräumplan', 'heading' => 'Aufräumplan', 'assets_dir' => __DIR__ . '/assets']);
    } catch (LoaderError|RuntimeError|SyntaxError $e)
    {
        echo $e->getMessage();
        die('Fehler im Rendering der Seite');
    }




