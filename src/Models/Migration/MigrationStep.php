<?php declare(strict_types=1);

    namespace Florian\Abfallkalender\Models\Migration;

    use Doctrine\DBAL\Connection;

    abstract class MigrationStep
    {
        abstract public function up(Connection $connection);
    }