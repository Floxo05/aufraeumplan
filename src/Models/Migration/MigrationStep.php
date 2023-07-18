<?php declare(strict_types=1);

    namespace Florian\Abfallkalender\Models\Migration;

    abstract class MigrationStep
    {
        protected string $sql;

        /**
         * @param string $sql
         */
        public function setSql(string $sql): void
        {
            $this->sql = $sql;
        }

        abstract public function up();
    }