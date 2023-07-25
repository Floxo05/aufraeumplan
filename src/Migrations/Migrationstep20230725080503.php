<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Migrations;

    use Doctrine\DBAL\Exception;
    use Doctrine\DBAL\Schema\SchemaException;
    use Doctrine\DBAL\Schema\Table;
    use Florian\Abfallkalender\Models\Migration\MigrationStep;
    use Doctrine\DBAL\Connection;

    class Migrationstep20230725080503 extends MigrationStep
    {
        // Implementiere hier die Migration
        /**
         * @throws SchemaException
         * @throws Exception
         */
        public function up(Connection $connection): void
        {
            $schema = $connection->createSchemaManager();

            $aktivitaetenTable = new Table('aktivitaeten');
            $aktivitaetenTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
            $aktivitaetenTable->addColumn('bezeichnung', 'string', ['length' => 255]);
            $aktivitaetenTable->addColumn('intervall', 'integer', ['comment' => 'Wiederholungsintervall in Tagen']);
            $aktivitaetenTable->addColumn('aktiv', 'boolean', ['default' => 0]);
            $aktivitaetenTable->addColumn('startdatum', 'date');
            $aktivitaetenTable->addColumn('user_id', 'integer', ['unsigned' => true, 'notnull' => false]);
            $aktivitaetenTable->addForeignKeyConstraint('user', ['user_id'], ['id'], ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']);

            $aktivitaetenTable->setPrimaryKey(['id']);

            $schema->createTable($aktivitaetenTable);
        }
    }
