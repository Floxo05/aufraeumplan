<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Models;

    use DateTime;
    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Exception;

    class Abfallkalender
    {

        const DATE_FORMAT = 'Y-m-d';
        protected array $aktivitaeten = [];
        protected Connection $conn;

        public function __construct(Connection $connection)
        {
            $this->conn = $connection;

            $this->loadAktivitaeten();
        }


        /**
         * @throws Exception
         */
        public function getAufgaben(string $date): array
        {
            $aufgaben = array_merge(
                $this->calcAufgabenFuerTag($date),
                $this->calcOffeneAufgaben($date)
            );

            for ($i = 0; $i < count($aufgaben); $i++)
            {
                unset($aufgaben[$i]['raum_id']);
                unset($aufgaben[$i]['user_id']);
                unset($aufgaben[$i]['intervall']);
                unset($aufgaben[$i]['startdatum']);
                $aufgaben[$i]['icon'] = $this->getIcon((int) $aufgaben[$i]['ist_erledigt']);
                if (!isset($aufgaben[$i]['ursprungs_datum']))
                {
                    $aufgaben[$i]['ursprungs_datum'] = $date;
                }
                unset($aufgaben[$i]['ist_erledigt']);
            }

            return $aufgaben;
        }

        /**
         * @throws Exception
         */
        private function loadAktivitaeten(): void
        {
            $query = $this->conn->createQueryBuilder();

            $where = $query->expr()->eq('a.aktiv', 1);

            $query->select('a.id as aktivitaeten_id, r.id as raum_id, u.id as user_id, a.bezeichnung as aktivitaet, r.bezeichnung as raum, u.name, a.intervall, a.startdatum')
                ->from('aktivitaeten', 'a')
                ->leftJoin('a', 'raeume', 'r', 'a.raum_id = r.id')
                ->leftJoin('a', 'user', 'u', 'a.user_id = u.id')
                ->where($where);

            $this->aktivitaeten = $query->executeQuery()->fetchAllAssociative();
        }

        /**
         * @throws Exception
         */
        private function calcAufgabenFuerTag(string $date): array
        {

            $aufgaben = [];

            $dateObject = DateTime::createFromFormat(self::DATE_FORMAT, $date);

            foreach ($this->aktivitaeten as $aktivitaet)
            {
                $dateAktivitaet = DateTime::createFromFormat(self::DATE_FORMAT, $aktivitaet['startdatum']);

                $diff = $dateObject->diff($dateAktivitaet);

                if ($diff->d % (int) $aktivitaet['intervall'] === 0)
                {
                    $query = $this->conn->createQueryBuilder();

                    $where = $query->expr()->and(
                        $query->expr()->eq($aktivitaet['aktivitaeten_id'], 'aktivitaeten_id'),
                        $query->expr()->eq('?', 'datum')
                    );

                    $query->select('ist_erledigt')
                        ->from('aktivitaeten_log')
                        ->where($where)
                        ->setParameter(1, $date);

                    $result = $query->fetchOne();

                    // Wenn $result ein bool ist, dann wurde kein Eintrag gefunden -> es wurde noch nicht bearbeitet
                    if (is_bool($result))
                    {
                        $result = 2;
                    }

                    $aktivitaet['ist_erledigt'] = $result;
                    $aufgaben[] = $aktivitaet;
                }
            }

            return $aufgaben;
        }

        /**
         * @throws Exception
         */
        public function updateAktivitaeten(array $requestData): void
        {
            $selectedIds = $requestData['selectedIDs'];
            for ($i = 0; $i < count($selectedIds); $i++)
            {
                $sql = <<<SQL
                INSERT INTO aktivitaeten_log
                SET
                    aktivitaeten_id = :aktivitaeten_id,
                    datum = :datum,
                    ist_erledigt = :ist_erledigt,
                    updated = NOW()
                ON DUPLICATE KEY UPDATE 
                    ist_erledigt = :ist_erledigt,
                    updated = NOW()                                       
SQL;

                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue('aktivitaeten_id', $selectedIds[$i]);
                $stmt->bindValue('datum', $requestData['dates'][$i]);
                $stmt->bindValue('ist_erledigt', $requestData['isDone'] ? 1 : 0);

                $stmt->executeQuery();
            }
        }

        /**
         * Funktion gibt den entsprechenden Iconname anhand des übergebenen Wertes zurück
         *
         * @param int $ist_erledigt
         * @return string
         */
        private function getIcon(int $ist_erledigt): string
        {
            return match ($ist_erledigt)
            {
                0 => 'cross',
                1 => 'tick',
                default => 'clock'
            };
        }

        /**
         * @throws Exception
         */
        public function calcOffeneAufgaben(string $date) : array
        {
            $out = [];

            $datetime = DateTime::createFromFormat(self::DATE_FORMAT, $date);

            for ($i = 0; $i < 14; $i++)
            {
                $datetime->modify("-1 day");

                $dateStr = $datetime->format(self::DATE_FORMAT);
                foreach ($this->calcAufgabenFuerTag($dateStr) as $aktivitaeten)
                {
                    if ($aktivitaeten['ist_erledigt'] === 2)
                    {
                        $aktivitaeten['ursprungs_datum'] = $dateStr;
                        $out[] = $aktivitaeten;
                    }
                }

            }

            return $out;
        }


    }