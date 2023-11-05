<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

interface BuildingFactoryInterface
{
    public function createNew(
        PlanetIdInterface $planetId,
        BuildingType $type,
        ResourceIdInterface $resourceContextId,
    ): Building;
}
