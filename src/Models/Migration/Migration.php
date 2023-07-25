<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Models\Migration;

    use DirectoryIterator;
    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Exception;
    use Doctrine\DBAL\Schema\SchemaException;
    use Doctrine\DBAL\Schema\Table;
    use Florian\Abfallkalender\Exceptions\MissingEnvironmentInformation;
    use Florian\Abfallkalender\Models\Environment\EnvironmentChecker;

    class Migration
    {
        const ENVIRONMENT_TABLE_NAME = 'MIGRATION_TABLE';
        const ENVIRONMENT_MIGRATIONS_FOLDER_PATH = 'PATH_MIGRATION';

        protected Connection $conn;
        protected string $path;

        /**
         * @param Connection $conn
         * @param string $path
         * @throws MissingEnvironmentInformation
         */
        public function __construct(Connection $conn, string $path)
        {
            $this->conn = $conn;
            $this->path = $path;

            EnvironmentChecker::check(self::ENVIRONMENT_TABLE_NAME);
            EnvironmentChecker::check(self::ENVIRONMENT_MIGRATIONS_FOLDER_PATH);
        }

        /**
         * @throws MissingEnvironmentInformation
         * @throws Exception
         * @throws SchemaException
         */
        public function doMigration(): void
        {
            //Check, ob Migrationsdatenbank vorhanden ist
            if (!$this->isMigrationDatabaseAvailable())
            {
                $this->createMigrationDatabase();
            }

            $migrationSteps = $this->getMigrationSteps();

            $this->executeMigrationSteps($migrationSteps);
        }

        /**
         * @throws Exception
         */
        private function isMigrationDatabaseAvailable(): bool
        {
            $sm = $this->conn->createSchemaManager();

            return $sm->tablesExist($_ENV[ self::ENVIRONMENT_TABLE_NAME ]);
        }

        /**
         * @throws SchemaException
         * @throws Exception
         */
        private function createMigrationDatabase(): void
        {
            $schema = $this->conn->createSchemaManager();

            $migrationTable = new Table($_ENV[ self::ENVIRONMENT_TABLE_NAME ]);
            $migrationTable->addColumn('name', 'string', ['length' => 255]);
            $migrationTable->setPrimaryKey(['name']);
            $migrationTable->addColumn('is_migrated', 'boolean');
            $migrationTable->addColumn('serialized_class', 'text');
            $migrationTable->addColumn('created_at', 'datetime');

            $schema->createTable($migrationTable);

            echo 'Database created: ' . $_ENV[ self::ENVIRONMENT_TABLE_NAME ] . PHP_EOL;
        }

        /**
         * @return MigrationStep[]
         * @throws MissingEnvironmentInformation
         */
        private function getMigrationSteps(): array
        {
            // Pfad zum Ordner mit den PHP-Dateien
            $ordnerPfad = __DIR__ . '/../../../' . $_ENV[ self::ENVIRONMENT_MIGRATIONS_FOLDER_PATH ];

            // Array, in das die Instanzen der Klassen gespeichert werden sollen
            $klassenInstanzen = array();

            // Dateien im Ordner durchsuchen
            $verzeichnis = new DirectoryIterator($ordnerPfad);
            /** @var DirectoryIterator $datei */
            foreach ($verzeichnis as $datei)
            {
                if ($datei->isFile() && $datei->getExtension() === 'php')
                {
                    // Klassenname aus dem Dateinamen ableiten (Annahme: Dateiname entspricht dem Klassenname)
                    $klassenname = $datei->getBasename('.php');

                    // Klasse einbinden und instanziieren
                    require_once $datei->getPathname();

                    $namespace = 'Florian\\Abfallkalender\\Migrations\\';
                    $klassenInstanzen[] = new ($namespace . $klassenname);
                }
            }

            return $klassenInstanzen;
        }


        public function createMigrationStep(): void
        {

            // Dateiname erstellen
            $fileName = 'Migrationstep' . date('YmdHis') . '.php';

            // Pfad zur neuen Datei
            $filePath = $_ENV[ self::ENVIRONMENT_MIGRATIONS_FOLDER_PATH ] . '/' . $fileName; # '../../..' . $_ENV[self::ENVIRONMENT_MIGRATIONS_FOLDER_PATH] . '/' .

            // Dateiinhalt erstellen
            $fileContent = "<?php declare(strict_types=1);\n\n";
            $fileContent .= "namespace Florian\\Abfallkalender\\Migrations;\n\n";
            $fileContent .= "use Florian\\Abfallkalender\\Models\\Migration\\MigrationStep;\n";
            $fileContent .= "use Doctrine\\DBAL\\Connection;\n";
            $fileContent .= "class Migrationstep" . date('YmdHis') . " extends MigrationStep\n";
            $fileContent .= "{\n";
            $fileContent .= "    // Implementiere hier die Migration\n";
            $fileContent .= "}\n";

            // Neue Datei schreiben
            if (file_put_contents($filePath, $fileContent) !== false)
            {
                // Erfolgsmeldung
                echo "Die Datei wurde erfolgreich erstellt: $filePath" . PHP_EOL;
            } else
            {
                // Fehlermeldung
                echo "Fehler beim Erstellen der Datei." . PHP_EOL;
            }

        }

        /**
         * @throws Exception
         * @throws \Exception
         */
        private function executeMigrationSteps(array $migrationSteps): void
        {
            $name = $_ENV['MIGRATION_TABLE'];
            $query = $this->conn->createQueryBuilder();
            $query->select('name', 'is_migrated', 'serialized_class', 'created_at')
                ->from($name)
                ->orderBy('created_at');


            $migrations = $query->executeQuery()->fetchAllAssociativeIndexed();
            $migrating = false;

            sort($migrationSteps);

            /** @var MigrationStep $migrationStep */
            foreach ($migrationSteps as $migrationStep)
            {
                if ($migrations[ $migrationStep::class ]['is_migrated'] ?? false)
                {
                    if ($migrating)
                    {
                        throw new \Exception('Migration ist bei ' . $migrationStep::class . ' fehlgeschlagen');
                    }
                    continue;
                }

                var_dump($migrationStep::class);

                $migrationStep->up($this->conn);
                $migrating = true;
                $this->addMigration($migrationStep);
            }
        }

        /**
         * @throws Exception
         */
        private function addMigration(MigrationStep $migrationStep): void
        {
            $query = $this->conn->createQueryBuilder();

            $query->insert($_ENV[ self::ENVIRONMENT_TABLE_NAME ])
                ->setValue('name', '?')
                ->setValue('is_migrated', '?')
                ->setValue('serialized_class', '?')
                ->setValue('created_at', '?')
                ->setParameter(0, $migrationStep::class)
                ->setParameter(1, 1)
                ->setParameter(2, serialize($migrationStep))
                ->setParameter(3, date('Y-m-d H:i:s'));

            $query->executeQuery();
        }


    }