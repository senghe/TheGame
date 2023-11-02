<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class BuildingFactory
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function createNew(
        PlanetIdInterface $planetId,
        BuildingType $type,
    ): Building {
        $buildingId = $this->uuidGenerator->generateNewBuildingId();

        return new Building(
            $planetId,
            $buildingId,
            $type,
        );
    }
}
