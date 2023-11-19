<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Command\StartJourneyCommand;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasStartedJourneyEvent;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NoFleetStationingOnPlanetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\Factory\JourneyFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\GalaxyPoint;
use TheGame\Application\Component\FleetJourney\Domain\GalaxyPointInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartJourneyCommandHandler
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly JourneyFactoryInterface $journeyFactory,
        private readonly NavigatorInterface $galaxyNavigator,
        private readonly FleetJourneyContextInterface $journeyContext,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(StartJourneyCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $stationingFleet = $this->fleetRepository->findStationingOnPlanet($planetId);
        if ($stationingFleet === null) {
            throw new NoFleetStationingOnPlanetException($planetId);
        }

        $startGalaxyPoint = $stationingFleet->getStationingPoint();
        $targetGalaxyPoint = new GalaxyPoint(
            $command->getTargetGalaxy(),
            $command->getTargetSolarSystem(),
            $command->getTargetPlanet(),
        );

        if ($this->isWithinBoundaries($targetGalaxyPoint) === false) {
            throw new CannotTakeJourneyToOutOfBoundGalaxyPointException($targetGalaxyPoint);
        }

        $fuelRequirements = $this->getFuelRequirements(
            $startGalaxyPoint, $targetGalaxyPoint, $command->getShipsTakingJourney(),
        );
        $hasEnoughFuelOnPlanet = $this->resourceAvailabilityChecker->check($planetId, $fuelRequirements);
        if ($hasEnoughFuelOnPlanet === false) {
            throw new NotEnoughFuelOnPlanetException($planetId);
        }

        $fleetTakingJourney = $stationingFleet;
        $shipsTakingJourney = $command->getShipsTakingJourney();
        if ($stationingFleet->hasEnoughShips($shipsTakingJourney) === false) {
            throw new NotEnoughShipsException($planetId);
        }

        if ($stationingFleet->hasMoreShipsThan($shipsTakingJourney)) {
            $shipsTakingJourney = $stationingFleet->split($shipsTakingJourney);
            $fleetTakingJourney = $this->fleetFactory->create(
                $stationingFleet->getStationingPoint(),
                $shipsTakingJourney,
            );
        }

        $resourcesLoadTotal = $this->calculateResourcesLoadTotal($command->getResourcesLoad());
        $fuelRequirementsTotal = $fuelRequirements->sum();
        $loadAvailable = $fleetTakingJourney->getLoad();
        if ($resourcesLoadTotal + $fuelRequirementsTotal > $loadAvailable) {
            throw new NotEnoughFleetLoadException($loadAvailable);
        }

        $journeyDuration = $this->getJourneyDuration(
            $fleetTakingJourney->getSpeed(), $startGalaxyPoint, $targetGalaxyPoint,
        );
        $journey = $this->journeyFactory->createJourney(
            MissionType::from($command->getMissionType()),
            $stationingFleet->getStationingPoint(),
            $targetGalaxyPoint,
            $journeyDuration,
        );

        $fleetTakingJourney->startJourney($journey);

        $this->eventBus->dispatch(
            new FleetHasStartedJourneyEvent(
                $fleetTakingJourney->getId()->getUuid(),
                $startGalaxyPoint->getGalaxy(),
                $startGalaxyPoint->getSolarSystem(),
                $startGalaxyPoint->getPlanet(),
                $targetGalaxyPoint->getGalaxy(),
                $targetGalaxyPoint->getSolarSystem(),
                $targetGalaxyPoint->getPlanet(),
                $journeyDuration,
                $fuelRequirements->toScalarArray(),
                $command->getResourcesLoad(),
            )
        );
    }

    private function isWithinBoundaries(GalaxyPointInterface $targetGalaxyPoint): bool
    {
        return $this->galaxyNavigator->isWithinBoundaries(
            $targetGalaxyPoint->getGalaxy(),
            $targetGalaxyPoint->getSolarSystem(),
            $targetGalaxyPoint->getPlanet(),
        );
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

    private function getFuelRequirements(
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        array $shipsTakingJourney,
    ): ResourcesInterface {
        return $this->journeyContext->getJourneyFuelNeeds(
            $startGalaxyPoint->getGalaxy(),
            $startGalaxyPoint->getSolarSystem(),
            $startGalaxyPoint->getPlanet(),
            $targetGalaxyPoint->getGalaxy(),
            $targetGalaxyPoint->getSolarSystem(),
            $targetGalaxyPoint->getPlanet(),
            $shipsTakingJourney,
        );
    }

    private function getJourneyDuration(
        int $fleetSpeed,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
    ): int {
        return $this->journeyContext->getJourneyDuration(
            $fleetSpeed,
            $startGalaxyPoint->getGalaxy(),
            $startGalaxyPoint->getSolarSystem(),
            $startGalaxyPoint->getPlanet(),
            $targetGalaxyPoint->getGalaxy(),
            $targetGalaxyPoint->getSolarSystem(),
            $targetGalaxyPoint->getPlanet(),
        );
    }
}
