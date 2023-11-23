<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class CancelJourneyCommand implements CommandInterface
{
    public function __construct(
        private readonly string $fleetId,
    ) {
    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }
}
