<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Migrations;

    use Doctrine\DBAL\Exception;
    use Doctrine\DBAL\Schema\Comparator;
    use Doctrine\DBAL\Schema\SchemaException;
    use Doctrine\DBAL\Schema\Table;
    use Florian\Abfallkalender\Models\Migration\MigrationStep;
    use Doctrine\DBAL\Connection;

    class Migrationstep20230725115909 extends MigrationStep
    {
        // Implementiere hier die Migration
        /**
         * @throws SchemaException
         * @throws Exception
         */
        public function up(Connection $connection)
        {
            $schema = $connection->createSchemaManager();

            //  Neue Tabelle erstellen
            $raumTable = new Table('raeume');
            $raumTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
            $raumTable->addColumn('bezeichnung', 'string', ['length' => 255]);
            $raumTable->setPrimaryKey(['id']);

            $schema->createTable($raumTable);

            // Aktivitäten um Fremdschlüssel raum erweitern
            $aktivitaetenTableDef = clone $schema->introspectTable('aktivitaeten');
            $aktivitaetenTableDef->addColumn('raum_id', 'integer', ['unsigned' => true, 'notnull' => false]);
            $aktivitaetenTableDef->addForeignKeyConstraint($raumTable, ['raum_id'], ['id'], ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']);

            $comparator = new Comparator();
            $diff = $comparator->compareTables($schema->introspectTable('aktivitaeten'), $aktivitaetenTableDef);
            $schema->alterTable($diff);


            $query = $connection->createQueryBuilder();

            $daten = [
              'Flur',
              'Bad',
              'Küche',
              'Stube',
              'Schlafzimmer'
            ];

            foreach ($daten as $raum)
            {
                $query->insert('raeume')
                    ->setValue('bezeichnung', '?')
                    ->setParameter(0, $raum);

                $query->executeQuery();
            }
        }
    }
