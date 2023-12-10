<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain;

use TheGame\Application\SharedKernel\UuidInterface;

class SolarSystemId implements SolarSystemIdInterface
{
    public function __construct(
        private readonly string $id
    ) {
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}
