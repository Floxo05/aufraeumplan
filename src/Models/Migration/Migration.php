<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Models\Migration;

    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Exception;
    use Florian\Abfallkalender\Exceptions\MissingEnvironmentInformation;

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
            var_dump($isMigrationDatabaseAvailable);
        }

        /**
         * @throws Exception
         * @throws MissingEnvironmentInformation
         */
        private function isMigrationDatabaseAvailable(): bool
        {
            $sm = $this->conn->createSchemaManager();
            $tables = $sm->listTables();

            if (!isset($_ENV['MIGRATION_TABLE']))
            {
                throw new MissingEnvironmentInformation('MIGRATION_TABLE');
            }

            foreach ($tables as $table)
            {
                if ($table->getName() === $_ENV['MIGRATION_TABLE'])
                {
                    return true;
                }
            }

            return false;
        }


    }