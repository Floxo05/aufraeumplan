<?php declare(strict_types=1);

    namespace Florian\Abfallkalender\Exceptions;

    use JetBrains\PhpStorm\Pure;

    class setPropertyException extends \Exception
    {
        #[Pure] public function __construct(string $message = "")
        {
            parent::__construct($message, 500, null);
        }

    }