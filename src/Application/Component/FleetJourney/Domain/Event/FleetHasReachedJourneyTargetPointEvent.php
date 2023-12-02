<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Event;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\EventInterface;

final class FleetHasReachedJourneyTargetPointEvent implements EventInterface
{
    /** @param array<string, int> $resourcesLoad */
    public function __construct(
        private readonly string $mission,
        private readonly string $fleetId,
        private readonly string $targetGalaxyPoint,
        private readonly array $resourcesLoad,
    ) {
        foreach ($this->resourcesLoad as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid resources load key or value');
            }
        }
    }

    public function getMission(): string
    {
        return $this->mission;
    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getTargetGalaxyPoint(): string
    {
        return $this->targetGalaxyPoint;
    }

    /** @return array<string, int> */
    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad;
    }
}
