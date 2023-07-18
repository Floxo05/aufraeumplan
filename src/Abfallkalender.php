<?php declare(strict_types=1);

    namespace Florian\Abfallkalender;

    use DateTime;
    use Florian\Abfallkalender\Exceptions\setPropertyException;

    class Abfallkalender
    {

        private int $userid = -1;
        private ?DateTime $endDatum = null;
        private ?bool $mitErinnerung = null;

        public function getKalender() {}

        public function getKalenderSkelett(string $inhalt = ''): string
        {
            return 'BEGIN:VCALENDER' . PHP_EOL .
                'VERSION:2.0' . PHP_EOL .
                'CALSCALE:GREGORIAN' . PHP_EOL .
                $inhalt .
                'END:VCALENDAR';
        }

        /**
         * @param int $userid
         * @throws setPropertyException
         */
        public function setUserid(int $userid): void
        {
            if ($userid <= 0)
            {
                throw new setPropertyException('Userid konnte nicht gesetzt werden');
            }

            $this->userid = $userid;
        }

        /**
         * @param DateTime|null $endDatum
         */
        public function setEndDatum(?DateTime $endDatum): void
        {
            $this->endDatum = $endDatum;
        }

        /**
         * @param bool|null $mitErinnerung
         */
        public function setMitErinnerung(?bool $mitErinnerung): void
        {
            $this->mitErinnerung = $mitErinnerung;
        }

        /**
         * @return int
         */
        public function getUserid(): int
        {
            return $this->userid;
        }

        /**
         * @return DateTime|null
         */
        public function getEndDatum(): ?DateTime
        {
            return $this->endDatum;
        }

        /**
         * @return bool|null
         */
        public function getMitErinnerung(): ?bool
        {
            return $this->mitErinnerung;
        }


        /**
         * @throws setPropertyException
         */
        public function setEndDatumFromString(string $endDatum): void
        {
            if (preg_match('/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|1\d|2[0-9]|3[01])$/', $endDatum))
            {
                // Versuchen, ein DateTime-Objekt aus dem String zu erstellen
                $date = DateTime::createFromFormat('Y-m-d', $endDatum);

                // Überprüfen, ob das DateTime-Objekt erfolgreich erstellt wurde
                if ($date !== false)
                {
                    $this->endDatum = $date;
                    return;
                }

                throw new setPropertyException('DateTime Object konnte nicht erstellt werden');
            }
        }

        public function writeKalender(array $fileArray): string
        {

            return '';
        }


    }