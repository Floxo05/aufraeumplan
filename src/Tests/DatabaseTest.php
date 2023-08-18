<?php

namespace Florian\Abfallkalender\Tests;

use Dotenv\Dotenv;
use Florian\Abfallkalender\Models\Database\Database;
use PHPUnit\Framework\TestCase;
use TestDatabase;

class DatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once '../../vendor/autoload.php';
    }


    public function testConnection()
    {

        Dotenv::createImmutable(__DIR__ . '/../../')->load();

        $this->expectNotToPerformAssertions();
        $conn = Database::getConnection();

        $conn->connect();

    }

}
