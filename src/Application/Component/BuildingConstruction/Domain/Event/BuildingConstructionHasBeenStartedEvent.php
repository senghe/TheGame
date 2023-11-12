<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event;

use InvalidArgumentException;
use TheGame\Application\SharedKernel\EventInterface;

final class BuildingConstructionHasBeenStartedEvent implements EventInterface
{
    /** @phpstan-ignore-next-line The validation is done in constructor */
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingType,
        private readonly string $buildingId,
        private readonly int $newLevel,
        private readonly array $resourceRequirements,
    ) {
        foreach ($this->resourceRequirements as $key => $value) {
            if (is_string($key) === false || is_int($value) === false) {
                throw new InvalidArgumentException('Invalid resource requirements key or value');
            }
        }
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getBuildingType(): string
    {
        return $this->buildingType;
    }

    public function getBuildingId(): string
    {
        return $this->buildingId;
    }

    public function getNewLevel(): int
    {
        return $this->newLevel;
    }

    /** @return array<string, int> */
    public function getResourceRequirements(): array
    {
        return $this->resourceRequirements;
    }
}
