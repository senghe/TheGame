<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

class BuildingConstructionHasBeenFinishedEvent implements EventInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingType,
        private readonly int $currentLevel,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getBuildingType(): string
    {
        return $this->buildingType;
    }

    public function getLevel(): int
    {
        return $this->currentLevel;
    }
}
