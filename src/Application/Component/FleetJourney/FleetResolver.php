<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney;

use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NoFleetStationingOnPlanetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFuelOnPlanetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\Resources;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class FleetResolver implements FleetResolverInterface
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly FleetJourneyContextInterface $journeyContext,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
    ) {

    }

    /**
     * @param array<string, int> $shipsTakingJourney
     * @param array<string, int> $resourcesLoad
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

        $startGalaxyPoint = $stationingFleet->getStationingGalaxyPoint();
        $fuelRequirements = $this->resolveFuelOnPlanet(
            $planetId, $startGalaxyPoint, $targetGalaxyPoint, $shipsTakingJourney
        );

        $resolvedFleet = $stationingFleet;
        if ($stationingFleet->hasEnoughShips($shipsTakingJourney) === false) {
            throw new NotEnoughShipsException($planetId);
        }

        if ($stationingFleet->hasMoreShipsThan($shipsTakingJourney)) {
            $shipsTakingJourney = $stationingFleet->split($shipsTakingJourney);
            $resolvedFleet = $this->fleetFactory->create(
                $stationingFleet->getStationingGalaxyPoint(),
                $shipsTakingJourney,
                $resourcesLoad,
            );
        }

        $resourcesLoadTotal = $this->calculateResourcesLoadTotal($resourcesLoad);
        $fuelRequirementsTotal = $fuelRequirements->sum();
        $currentCapacity = $resolvedFleet->getLoadCapacity();
        $capacityNeeded = $resourcesLoadTotal + $fuelRequirementsTotal;
        if ($capacityNeeded > $currentCapacity) {
            throw new NotEnoughFleetLoadCapacityException(
                $resolvedFleet->getId(), $currentCapacity, $capacityNeeded
            );
        }

        return $resolvedFleet;
    }

    private function resolveFuelOnPlanet(
        PlanetIdInterface $planetId,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        array $shipsTakingJourney,
    ): ResourcesInterface {
        $fuelRequirements = $this->journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $shipsTakingJourney,
        );
        $hasEnoughFuelOnPlanet = $this->resourceAvailabilityChecker->check($planetId, $fuelRequirements);
        if ($hasEnoughFuelOnPlanet === false) {
            throw new NotEnoughFuelOnPlanetException($planetId);
        }

        return $fuelRequirements;
    }

    /** @param array<string, int> $resources */
    private function calculateResourcesLoadTotal(array $resources): int
    {
        $total = 0;
        foreach ($resources as $quantity) {
            $total += $quantity;
        }

        return $total;
    }
}
