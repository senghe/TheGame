<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class FleetId implements ResourceIdInterface
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
