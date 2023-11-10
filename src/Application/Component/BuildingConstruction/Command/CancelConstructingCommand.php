<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class CancelConstructingCommand implements CommandInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingId,
    ) {
    }

    public function getPlanetId(): string
    {
        return $this->planetId;
    }

    public function getBuildingId(): string
    {
        return $this->buildingId;
    }
}
