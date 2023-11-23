<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class StartJourneyCommand implements CommandInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $targetGalaxyPoint,
        private readonly string $missionType,
        private readonly array $shipsTakingJourney,
        private readonly array $resourcesLoad,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getTargetGalaxyPoint(): string
    {
        return $this->targetGalaxyPoint;
    }

    public function getMissionType(): string
    {
        return $this->missionType;
    }

    /** @return array<string, int> $shipsTakingJourney */
    public function getShipsTakingJourney(): array
    {
        return $this->shipsTakingJourney;
    }

    /** @return array<string, int> */
    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad;
    }
}
