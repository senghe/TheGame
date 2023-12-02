<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney;

use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NoFleetStationingOnPlanetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughResourcesOnPlanetForFleetLoadException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\Factory\FleetFactoryInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class FleetResolver implements FleetResolverInterface
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly FleetFactoryInterface $fleetFactory,
        private readonly FleetJourneyContextInterface $journeyContext,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
    ) {
    }

    /**
     * @param array<string, int> $shipsTakingJourney
     */
    public function resolveFromPlanet(
        PlanetIdInterface $planetId,
        array $shipsTakingJourney,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
    ): Fleet {
        $stationingFleet = $this->fleetRepository->findStationingOnPlanet($planetId);
        if ($stationingFleet === null) {
            throw new NoFleetStationingOnPlanetException($planetId);
        }

        if ($stationingFleet->hasEnoughShips($shipsTakingJourney) === false) {
            throw new NotEnoughShipsException($planetId);
        }

        $resolvedFleet = $stationingFleet;
        if ($stationingFleet->hasMoreShipsThan($shipsTakingJourney)) {
            $shipGroupsTakingJourney = $stationingFleet->split($shipsTakingJourney);
            $resolvedFleet = $this->fleetFactory->create(
                $shipGroupsTakingJourney,
                $stationingFleet->getStationingGalaxyPoint(),
                $resourcesLoad,
            );
        }

        $startGalaxyPoint = $stationingFleet->getStationingGalaxyPoint();
        $fuelRequirements = $this->journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $shipsTakingJourney,
        );

        $resourcesLoad->add($fuelRequirements);
        $hasEnoughResources = $this->resourceAvailabilityChecker->check($planetId, $resourcesLoad);
        if ($hasEnoughResources === false) {
            throw new NotEnoughResourcesOnPlanetForFleetLoadException($planetId);
        }
        $this->loadResources($resolvedFleet, $resourcesLoad);

        return $resolvedFleet;
    }

    private function loadResources(
        Fleet $resolvedFleet,
        ResourcesInterface $resourcesLoad,
    ): void {
        $capacityNeeded = $resourcesLoad->sum();
        $currentCapacity = $resolvedFleet->getLoadCapacity();
        if ($capacityNeeded > $currentCapacity) {
            throw new NotEnoughFleetLoadCapacityException(
                $resolvedFleet->getId(),
                $currentCapacity,
                $capacityNeeded
            );
        }

        $resolvedFleet->load($resourcesLoad);
    }
}
