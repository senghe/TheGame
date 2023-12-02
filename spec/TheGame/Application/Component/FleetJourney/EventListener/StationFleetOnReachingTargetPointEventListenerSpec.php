<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class StationFleetOnReachingTargetPointEventListenerSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        NavigatorInterface $navigator,
    ): void {
        $this->beConstructedWith($fleetRepository, $navigator);
    }

    public function it_does_nothing_when_mission_type_is_not_stationing(): void
    {
        $mission = "transport";
        $fleetId = "22610343-1ee7-4ef5-84b5-4e285a68dba5";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [];

        $event = new FleetHasReachedJourneyTargetPointEvent($mission, $fleetId, $targetGalaxyPoint, $resourcesLoad);
        $this->__invoke($event);
    }

    public function it_throws_exception_when_fleet_joining_the_planet_has_not_been_found(
        FleetRepositoryInterface $fleetRepository,
    ): void {
        $mission = "stationing";
        $fleetId = "22610343-1ee7-4ef5-84b5-4e285a68dba5";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [];

        $fleetRepository->find(new FleetId($fleetId))->willReturn(null);

        $event = new FleetHasReachedJourneyTargetPointEvent($mission, $fleetId, $targetGalaxyPoint, $resourcesLoad);
        $this->shouldThrow(InconsistentModelException::class)->during('__invoke', [$event]);
    }

    public function it_throws_exception_when_landing_target_planet_doesnt_exist(
        FleetRepositoryInterface $fleetRepository,
        NavigatorInterface $navigator,
        Fleet $joiningFleet,
    ): void {
        $mission = "stationing";
        $fleetId = "22610343-1ee7-4ef5-84b5-4e285a68dba5";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [];

        $resolvedTargetGalaxyPoint = new GalaxyPoint(1, 2, 3);
        $fleetRepository->find(new FleetId($fleetId))->willReturn($joiningFleet);
        $navigator->getPlanetId($resolvedTargetGalaxyPoint)
            ->willReturn(null);

        $event = new FleetHasReachedJourneyTargetPointEvent($mission, $fleetId, $targetGalaxyPoint, $resourcesLoad);
        $this->shouldThrow(InconsistentModelException::class)->during('__invoke', [$event]);
    }

    public function it_lands_on_planet_when_no_fleet_exists_on_the_planet(
        FleetRepositoryInterface $fleetRepository,
        NavigatorInterface $navigator,
        PlanetIdInterface $landingPlanetId,
        Fleet $joiningFleet,
    ): void {
        $mission = "stationing";
        $fleetId = "22610343-1ee7-4ef5-84b5-4e285a68dba5";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [];

        $resolvedTargetGalaxyPoint = new GalaxyPoint(1, 2, 3);
        $fleetRepository->find(new FleetId($fleetId))->willReturn($joiningFleet);
        $navigator->getPlanetId($resolvedTargetGalaxyPoint)
            ->willReturn($landingPlanetId);
        $fleetRepository->findStationingOnPlanet($landingPlanetId)
            ->willReturn(null);

        $joiningFleet->landOnPlanet($resolvedTargetGalaxyPoint)
            ->shouldBeCalledOnce();

        $event = new FleetHasReachedJourneyTargetPointEvent($mission, $fleetId, $targetGalaxyPoint, $resourcesLoad);
        $this->__invoke($event);
    }

    public function it_merges_incoming_fleet_with_the_fleet_already_stationing_on_planet(
        FleetRepositoryInterface $fleetRepository,
        NavigatorInterface $navigator,
        PlanetIdInterface $landingPlanetId,
        Fleet $alreadyStationingFleet,
        Fleet $joiningFleet,
    ): void {
        $mission = "stationing";
        $fleetId = "22610343-1ee7-4ef5-84b5-4e285a68dba5";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [];

        $resolvedTargetGalaxyPoint = new GalaxyPoint(1, 2, 3);
        $fleetRepository->find(new FleetId($fleetId))->willReturn($joiningFleet);
        $navigator->getPlanetId($resolvedTargetGalaxyPoint)
            ->willReturn($landingPlanetId);
        $fleetRepository->findStationingOnPlanet($landingPlanetId)
            ->willReturn($alreadyStationingFleet);

        $alreadyStationingFleet->merge($joiningFleet)
            ->shouldBeCalledOnce();

        $event = new FleetHasReachedJourneyTargetPointEvent($mission, $fleetId, $targetGalaxyPoint, $resourcesLoad);
        $this->__invoke($event);
    }
}
