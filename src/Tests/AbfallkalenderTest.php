<?php declare(strict_types=1);

    namespace Florian\Abfallkalender\Tests;

    use Florian\Abfallkalender\Abfallkalender;
    use Florian\Abfallkalender\Exceptions\setPropertyException;
    use PHPUnit\Framework\TestCase;

    class AbfallkalenderTest extends TestCase
    {
        protected Abfallkalender $abfallkalender;

        protected function setUp(): void
        {
            parent::setUp();

            $this->abfallkalender = new Abfallkalender();
        }


        /**
         * @throws setPropertyException
         */
        public function testSetUser() {
            $userid = 1;
            $userid_false = 0;

            $this->abfallkalender->setUserid($userid);

            $this->assertSame($userid, $this->abfallkalender->getUserid(), 'Right Userid');

            $this->expectException(setPropertyException::class);
            $this->abfallkalender->setUserid($userid_false);
        }

        public function testSetEndDatum() {
            $endDatum = '2023-07-14';
            $endDatum_false = [
                '2023',
                '2023-01-aa',
                '2023-23-19',
            ];

            $this->abfallkalender->setEndDatumFromString($endDatum);

            $this->assertIsObject($this->abfallkalender->getEndDatum());


            $this->abfallkalender = new Abfallkalender();
            foreach ($endDatum_false as $datum)
            {
                $this->abfallkalender->setEndDatumFromString($datum);
                $this->assertNull($this->abfallkalender->getEndDatum());
            }



        }



    }
