<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface FleetJourneyContextInterface
{
    public function getShipBaseSpeed(string $shipType): int;

    public function getShipLoadCapacity(string $type): int;

    public function calculateJourneyDuration(
        int $speed,
        GalaxyPointInterface $from,
        GalaxyPointInterface $to,
    ): int;

    /** @param array<string, int> $shipsTakingJourney */
    public function calculateFuelRequirements(
        GalaxyPointInterface $from,
        GalaxyPointInterface $to,
        array $shipsTakingJourney,
    ): ResourcesInterface;
}
