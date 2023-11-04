<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain\Exception;

use DomainException;

final class InvalidBuildingTypeException extends DomainException
{
    public function __construct(string $type)
    {
        $message = sprintf('Invalid building type %s', $type);

        parent::__construct($message);
    }
}
