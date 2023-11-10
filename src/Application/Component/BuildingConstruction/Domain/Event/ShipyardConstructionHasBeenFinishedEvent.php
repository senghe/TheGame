<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event;

use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\EventInterface;

final class ShipyardConstructionHasBeenFinishedEvent extends BuildingConstructionHasBeenFinishedEvent implements EventInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingId,
        private readonly int $upgradedLevel,
    ) {
        return parent::__construct(
            $this->planetId,
            BuildingType::Shipyard->value,
            $this->buildingId,
            $this->upgradedLevel,
        );
    }
}
