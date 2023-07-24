<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Migrations;

    use Doctrine\DBAL\Exception;
    use Doctrine\DBAL\Schema\SchemaException;
    use Doctrine\DBAL\Schema\Table;
    use Florian\Abfallkalender\Models\Migration\MigrationStep;
    use Doctrine\DBAL\Connection;

    class Migrationstep20230724093032 extends MigrationStep
    {
        // Implementiere hier die Migration
        /**
         * @throws SchemaException
         * @throws Exception
         */
        public function up(Connection $connection): void
        {
            $schema = $connection->createSchemaManager();

            $userTable = new Table('user');
            $userTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
            $userTable->addColumn('name', 'string', ['length' => 255]);
            $userTable->setPrimaryKey(['id']);

            $schema->createTable($userTable);
        }
    }
