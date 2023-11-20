<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Factory;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class FleetFactory implements FleetFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    /** @param array<ShipsGroupInterface> $shipsTakingJourney */
    public function create(
        array $shipsTakingJourney,
        GalaxyPointInterface $stationingPoint,
        ResourcesInterface $resourcesLoad,
    ): Fleet {
        $id = $this->uuidGenerator->generateNewFleetId();

        $fleet = new Fleet(
            $id,
            $stationingPoint,
            $resourcesLoad
        );
        $fleet->addShips($shipsTakingJourney);

        return $fleet;
    }
}
