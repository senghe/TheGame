<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Bridge;

use TheGame\Application\Component\Balance\Bridge\GalaxyContextInterface;
use TheGame\Application\Component\Galaxy\SolarSystemRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

final class Navigator implements NavigatorInterface
{
    public function __construct(
        private readonly SolarSystemRepositoryInterface $solarSystemRepository,
        private readonly GalaxyContextInterface $galaxyContext,
    ) {

    }

    public function getPlanetId(GalaxyPointInterface $galaxyPoint): ?PlanetIdInterface
    {
        $solarSystem = $this->solarSystemRepository->findByGalaxyPoint($galaxyPoint);

        return $solarSystem->getPlanetId($galaxyPoint->getPlanet());
    }

    public function getPlanetPosition(PlanetIdInterface $planetId): int
    {
        $solarSystem = $this->solarSystemRepository->findByPlanetId($planetId);

        return $solarSystem->getPlanetPosition($planetId);
    }

    public function isWithinBoundaries(GalaxyPointInterface $galaxyPoint): bool
    {
        if ($galaxyPoint->getGalaxy() < 1 || $galaxyPoint->getSolarSystem() < 1 || $galaxyPoint->getPlanet() < 1) {
            return false;
        }

        return $this->galaxyContext->getMaxGalaxyNumber() >= $galaxyPoint->getGalaxy()
            && $this->galaxyContext->getMaxSolarSystem() >= $galaxyPoint->getSolarSystem()
            && $this->galaxyContext->getMaxPlanetPosition() >= $galaxyPoint->getSolarSystem();
    }

    public function isMissionEligible(
        FleetMissionType     $missionType,
        GalaxyPointInterface $planetFrom,
        GalaxyPointInterface $planetTo
    ): bool {
        $startingSolarSystem = $this->solarSystemRepository->findByGalaxyPoint($planetFrom);
        $targetSolarSystem = $this->solarSystemRepository->findByGalaxyPoint($planetTo);

        $startPlanetOwner = $startingSolarSystem->getPlayerId($planetFrom->getPlanet());
        $targetPlanetOwner = $targetSolarSystem->getPlayerId($planetTo->getPlanet());
        $samePlayerOwnsPlanets = $startPlanetOwner->getUuid() === $targetPlanetOwner->getUuid();

        if ($missionType === FleetMissionType::Attack && $samePlayerOwnsPlanets) {
            return false;
        }

        $isTargetColonized = $targetSolarSystem->isColonized($planetTo->getPlanet());
        if ($missionType === FleetMissionType::Colonization && $isTargetColonized) {
            return false;
        }

        if ($missionType === FleetMissionType::Stationing && $samePlayerOwnsPlanets === false) {
            return false;
        }

        return true;
    }
}
