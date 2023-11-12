<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

interface ShipyardFactoryInterface
{
    public function create(
        PlanetIdInterface $planetId,
        BuildingIdInterface $buildingId,
    ): Shipyard;
}
