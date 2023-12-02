<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\EventListener;

use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class StationFleetOnReachingTargetPointEventListener
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly NavigatorInterface $navigator,
    ) {
    }

    public function __invoke(FleetHasReachedJourneyTargetPointEvent $event): void
    {
        $mission = MissionType::from($event->getMission());
        if ($mission !== MissionType::Stationing) {
            return;
        }

        $joiningFleetId = new FleetId($event->getFleetId());
        $fleetJoiningPlanet = $this->fleetRepository->find($joiningFleetId);
        if ($fleetJoiningPlanet === null) {
            throw new InconsistentModelException(sprintf('Fleet %s doesn\'t exist', $event->getFleetId()));
        }

        $stationingPoint = GalaxyPoint::fromString($event->getTargetGalaxyPoint());
        $planetId = $this->navigator->getPlanetId($stationingPoint);
        if ($planetId === null) {
            throw new InconsistentModelException(sprintf('Planet %s doesn\'t exist', $event->getTargetGalaxyPoint()));
        }

        $fleetCurrentlyStationingOnPlanet = $this->fleetRepository->findStationingOnPlanet($planetId);
        if ($fleetCurrentlyStationingOnPlanet === null) {
            $fleetJoiningPlanet->landOnPlanet($stationingPoint);

            return;
        }

        $fleetCurrentlyStationingOnPlanet->merge($fleetJoiningPlanet);
    }
}
