<?php declare(strict_types=1);

namespace Florian\Abfallkalender\Migrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Florian\Abfallkalender\Models\Migration\MigrationStep;
use Doctrine\DBAL\Connection;
class Migrationstep20230727115212 extends MigrationStep
{
    // Implementiere hier die Migration
    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function up(Connection $connection): void
    {
        $schema = $connection->createSchemaManager();

        $aktivitaetenLogTable = new Table('aktivitaeten_log');
        $aktivitaetenLogTable->addColumn('aktivitaeten_id', 'integer', ['unsigned' => true]);
        $aktivitaetenLogTable->addColumn('datum', 'date');
        $aktivitaetenLogTable->addColumn('ist_erledigt', 'boolean', ['default' => 0]);
        $aktivitaetenLogTable->addColumn('updated', 'datetime');

        $aktivitaetenLogTable->setPrimaryKey(['aktivitaeten_id', 'datum']);
        $aktivitaetenLogTable->addForeignKeyConstraint(
            'aktivitaeten',
        ['aktivitaeten_id'],
        ['id'],
        ['onUpdate' => 'CASCADE', 'onDelete' => 'NO ACTION']);

        $schema->createTable($aktivitaetenLogTable);
    }
}
