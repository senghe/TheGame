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
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartJourneyCommandHandler
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly JourneyFactoryInterface $journeyFactory,
        private readonly FleetJourneyContextInterface $journeyContext,
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

        $startGalaxyPoint = $fleetTakingJourney->getStationingPoint();
        $targetGalaxyPoint = new GalaxyPoint(
            $command->getTargetGalaxy(),
            $command->getTargetSolarSystem(),
            $command->getTargetPlanet(),
        );

        $journeyDuration = $this->journeyContext->getJourneyDuration(
            $fleetTakingJourney->getSpeed(),
            $startGalaxyPoint->getGalaxy(),
            $startGalaxyPoint->getSolarSystem(),
            $startGalaxyPoint->getPlanet(),
            $targetGalaxyPoint->getGalaxy(),
            $targetGalaxyPoint->getSolarSystem(),
            $targetGalaxyPoint->getPlanet(),
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
            )
        );
    }
}
