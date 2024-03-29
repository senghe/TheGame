<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Bridge;

use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

interface NavigatorInterface
{
    public function getPlanetId(GalaxyPointInterface $galaxyPoint): ?PlanetIdInterface;

    public function getPlanetPoint(PlanetIdInterface $planetId): GalaxyPointInterface;

    public function isWithinBoundaries(GalaxyPointInterface $galaxyPoint): bool;

    public function isColonized(GalaxyPointInterface $galaxyPoint): bool;

    public function isMissionEligible(
        string $missionType,
        GalaxyPointInterface $planetFrom,
        GalaxyPointInterface $planetTo,
    ): bool;
}
