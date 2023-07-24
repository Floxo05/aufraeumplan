<?php

    namespace Florian\Abfallkalender\Models\Environment;

    use Florian\Abfallkalender\Exceptions\MissingEnvironmentInformation;

    class EnvironmentChecker
    {
        /**
         * @throws MissingEnvironmentInformation
         */
        public static function check(string $variable_name): void
        {
            if (!isset($_ENV[$variable_name]))
            {
                throw new MissingEnvironmentInformation($variable_name);
            }
        }
    }