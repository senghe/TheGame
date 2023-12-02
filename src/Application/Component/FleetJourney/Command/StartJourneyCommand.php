<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Command;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\CommandInterface;

final class StartJourneyCommand implements CommandInterface
{
    /**
     * @param array<string, int> $shipsTakingJourney
     * @param array<string, int> $resourcesLoad
     */
    public function __construct(
        private readonly string $planetId,
        private readonly string $targetGalaxyPoint,
        private readonly string $missionType,
        private readonly array $shipsTakingJourney,
        private readonly array $resourcesLoad,
    ) {
        foreach ($this->shipsTakingJourney as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid ships taking journey key or value');
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

    public function getTargetGalaxyPoint(): string
    {
        return $this->targetGalaxyPoint;
    }

    public function getMissionType(): string
    {
        return $this->missionType;
    }

    /** @return array<string, int> */
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
