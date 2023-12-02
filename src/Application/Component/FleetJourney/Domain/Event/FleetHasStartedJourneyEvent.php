<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Event;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\EventInterface;

final class FleetHasStartedJourneyEvent implements EventInterface
{
    /**
     * @param array<string, int> $fuelRequirements
     * @param array<string, int> $resourcesLoad
     */
    public function __construct(
        private readonly string $planetId,
        private readonly string $fleetId,
        private readonly string $fromGalaxyPoint,
        private readonly string $targetGalaxyPoint,
        private readonly array $fuelRequirements,
        private readonly array $resourcesLoad,
    ) {
        foreach ($this->fuelRequirements as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid fuel requirements key or value');
            }
        }

        foreach ($this->resourcesLoad as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid resources load key or value');
            }
        }
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

    /** @return array<string, int> */
    public function getFuelRequirements(): array
    {
        return $this->fuelRequirements;
    }

    /** @return array<string, int> */
    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad;
    }
}
