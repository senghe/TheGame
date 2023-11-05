<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenFinishedEvent;

interface BuildingTypeEventFactoryInterface
{
    public function createConstructingFinishedEvent(Building $building): BuildingConstructionHasBeenFinishedEvent;
}
