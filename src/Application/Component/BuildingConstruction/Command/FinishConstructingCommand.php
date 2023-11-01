<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class FinishConstructingCommand implements CommandInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingType,
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
}
