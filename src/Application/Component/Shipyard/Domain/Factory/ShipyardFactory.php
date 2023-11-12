<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Factory;

use DateTimeImmutable;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class ShipyardFactory implements ShipyardFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function create(
        PlanetIdInterface $planetId,
        BuildingIdInterface $buildingId,
    ): Shipyard {
        $shipyardId = $this->uuidGenerator->generateNewShipyardId();
        $noProductionLimit = 0;

        return new Shipyard(
            $shipyardId,
            $planetId,
            $buildingId,
            $noProductionLimit,
            new DateTimeImmutable(),
        );
    }
}
