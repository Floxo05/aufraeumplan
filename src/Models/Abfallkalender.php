<?php
    declare(strict_types=1);

    namespace Florian\Abfallkalender\Models;

    use DateTime;
    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Exception;

    class Abfallkalender
    {
        protected array $aktivitaeten = [];
        protected Connection $conn;

        public function __construct(Connection $connection)
        {
            $this->conn = $connection;

            $this->loadAktivitaeten();
        }


        public function getAufgaben(string $date): array
        {
            $aufgaben = $this->calcAufgabenFuerTag($date);

            for ($i = 0; $i < count($aufgaben); $i++)
            {
                unset($aufgaben[$i]['raum_id']);
                unset($aufgaben[$i]['user_id']);
                unset($aufgaben[$i]['intervall']);
                unset($aufgaben[$i]['startdatum']);
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

        private function calcAufgabenFuerTag(string $date) {

            $aufgaben = [];

            $dateObject = DateTime::createFromFormat('Y-m-d', $date);

            foreach ($this->aktivitaeten as $aktivitaet)
            {
                $dateAktivitaet = DateTime::createFromFormat('Y-m-d', $aktivitaet['startdatum']);

                $diff = $dateObject->diff($dateAktivitaet);

                if ($diff->d % (int) $aktivitaet['intervall'] === 0)
                {
                    $aufgaben[] = $aktivitaet;
                }
            }

            return $aufgaben;
        }
    }