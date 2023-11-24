<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class FleetHasStartedJourneyEvent implements EventInterface
{
    /** @var array<string, int> $fuelRequirements */
    public function __construct(
        private readonly string $planetId,
        private readonly string $fleetId,
        private readonly string $fromGalaxyPoint,
        private readonly string $targetGalaxyPoint,
        private readonly array $fuelRequirements,
        private readonly array $resourcesLoad,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getFromGalaxyPoint(): string
    {
        return $this->fromGalaxyPoint;
    }

    public function getTargetGalaxyPoint(): string
    {
        return $this->targetGalaxyPoint;
    }

    public function getFuelRequirements(): array
    {
        return $this->fuelRequirements;
    }

    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad;
    }
}
