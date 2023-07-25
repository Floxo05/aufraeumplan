<?php declare(strict_types=1);

namespace Florian\Abfallkalender\Migrations;

use Doctrine\DBAL\Exception;
use Florian\Abfallkalender\Models\Migration\MigrationStep;
use Doctrine\DBAL\Connection;
class Migrationstep20230725090351 extends MigrationStep
{
    // Implementiere hier die Migration
    /**
     * @throws Exception
     */
    public function up(Connection $connection): void
    {
        $query = $connection->createQueryBuilder();

        $query->insert('user')
            ->setValue('name', '?')
            ->setParameter(0, 'Emilia');

        $query->executeQuery();

        $query->insert('user')
            ->setValue('name', '?')
            ->setParameter(0, 'Flo');

        $query->executeQuery();
    }
}
