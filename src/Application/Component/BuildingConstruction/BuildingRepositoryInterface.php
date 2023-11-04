<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction;

use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

interface BuildingRepositoryInterface
{
    public function findForPlanet(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
    ): ?Building;
}
