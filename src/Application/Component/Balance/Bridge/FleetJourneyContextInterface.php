<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface FleetJourneyContextInterface
{
    public function getShipClass(string $shipName): string;

    public function getShipBaseSpeed(string $shipName): int;

    public function getShipLoadCapacity(string $shipName): int;

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
