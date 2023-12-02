<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Factory;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface FleetFactoryInterface
{
    /** @param array<ShipsGroupInterface> $shipsTakingJourney */
    public function create(
        array $shipsTakingJourney,
        GalaxyPointInterface $stationingPoint,
        ResourcesInterface $resourcesLoad,
    ): Fleet;
}
