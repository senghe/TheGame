<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

final class JourneyId implements JourneyIdInterface
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
