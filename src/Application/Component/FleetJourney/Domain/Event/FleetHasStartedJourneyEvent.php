<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class FleetHasStartedJourneyEvent implements EventInterface
{
    public function __construct(
        private readonly string $fleetId,
        private readonly int $fromGalaxy,
        private readonly int $fromSolarSystem,
        private readonly int $fromPlanet,
        private readonly int $toGalaxy,
        private readonly int $toSolarSystem,
        private readonly int $toPlanet,
        private readonly int $journeyDuration,
    ) {

    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getFromGalaxy(): int
    {
        return $this->fromGalaxy;
    }

    public function getFromSolarSystem(): int
    {
        return $this->fromSolarSystem;
    }

    public function getFromPlanet(): int
    {
        return $this->fromPlanet;
    }

    public function getToGalaxy(): int
    {
        return $this->toGalaxy;
    }

    public function getToSolarSystem(): int
    {
        return $this->toSolarSystem;
    }

    public function getToPlanet(): int
    {
        return $this->toPlanet;
    }

    public function getJourneyDuration(): int
    {
        return $this->journeyDuration;
    }
}
