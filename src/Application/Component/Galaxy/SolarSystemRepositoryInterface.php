<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy;

use TheGame\Application\Component\Galaxy\Domain\Entity\SolarSystem;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

interface SolarSystemRepositoryInterface
{
    public function findByGalaxyPoint(GalaxyPointInterface $galaxyPoint): ?SolarSystem;

    public function findByPlanetId(PlanetIdInterface $planetId): ?SolarSystem;
}
