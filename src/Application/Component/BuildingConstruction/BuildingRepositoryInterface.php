<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;

interface BuildingRepositoryInterface
{
    public function findForPlanetByType(
        PlanetIdInterface $planetId,
        BuildingType $buildingType,
        ?ResourceIdInterface $resourceContextId,
    ): ?Building;

    public function findForPlanetById(
        PlanetIdInterface $planetId,
        BuildingIdInterface $buildingId,
    ): ?Building;
}
