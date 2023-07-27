<?php

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use Florian\Abfallkalender\Models\Abfallkalender;
use Florian\Abfallkalender\Models\Database\Database;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requestData = json_decode(file_get_contents("php://input"), true);

    if (isset($requestData["selectedIDs"]) && isset($requestData["isDone"])) {

        try {
            $conn = Database::getConnection();
        } catch (\Doctrine\DBAL\Exception $e) {
            echo json_encode([
                'Message' => $e->getMessage(),
                'status' => 500]
            );
            die();
        }

        $abfallkalender = new Abfallkalender($conn);

        try {
            $abfallkalender->updateAktivitaeten($requestData);
        } catch (\Doctrine\DBAL\Exception $e) {
            echo json_encode([
                    'Message' => $e->getMessage(),
                    'status' => 500]
            );
            die();
        }

        echo json_encode([
            'Message' => 'Daten erfolgreich gespeichert',
            'status' => 200
        ]);
    }
}
