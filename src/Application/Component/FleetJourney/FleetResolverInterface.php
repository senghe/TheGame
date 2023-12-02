<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface FleetResolverInterface
{
    /**
     * @param array<string, int> $shipsTakingJourney
     */
    public function resolveFromPlanet(
        PlanetIdInterface $planetId,
        array $shipsTakingJourney,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
    ): Fleet;
}
