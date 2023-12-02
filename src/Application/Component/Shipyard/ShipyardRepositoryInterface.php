<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

interface ShipyardRepositoryInterface
{
    public function find(
        ShipyardIdInterface $shipyardId
    ): ?Shipyard;

    public function findForBuilding(
        PlanetIdInterface $planetId,
        BuildingIdInterface $buildingId
    ): ?Shipyard;
}
