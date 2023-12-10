<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Bridge;

use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

interface NavigatorInterface
{
    public function getPlanetId(GalaxyPointInterface $galaxyPoint): ?PlanetIdInterface;

    public function getPlanetPosition(PlanetIdInterface $planetId): int;

    public function isWithinBoundaries(GalaxyPointInterface $galaxyPoint): bool;

    public function isMissionEligible(
        FleetMissionType     $missionType,
        GalaxyPointInterface $planetFrom,
        GalaxyPointInterface $planetTo,
    ): bool;
}
