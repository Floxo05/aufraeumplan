<?php

namespace Florian\Abfallkalender\Exceptions;

use JetBrains\PhpStorm\Pure;

class MissingEnvironmentInformation extends \Exception
{
    #[Pure] public function __construct(string $variableName)
    {
        $message = "Environmentvariable $variableName ist nicht gesetzt";
        parent::__construct($message, 500);
    }

}