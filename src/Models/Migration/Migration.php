<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Models\Migration;

    use Doctrine\DBAL\Connection;

    class Migration
    {
        protected Connection $conn;
        protected string $path;

        /**
         * @param Connection $conn
         * @param string $path
         */
        public function __construct(Connection $conn, string $path)
        {
            $this->conn = $conn;
            $this->path = $path;
        }

        public function doMigration()
        {

            //Check, ob Migrationsdatenbank vorhanden ist
            $isMigrationDatabaseAvailable = $this->isMigrationDatabaseAvailable();
        }

        private function isMigrationDatabaseAvailable(): bool
        {
            $sm = $this->conn->createSchemaManager();

            var_dump($sm->listTables());

            return false;
        }


    }