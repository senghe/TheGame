<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NoFleetStationingOnPlanetException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughFleetLoadCapacityException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughResourcesOnPlanetForFleetLoadException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\NotEnoughShipsException;
use TheGame\Application\Component\FleetJourney\Domain\Factory\FleetFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroupInterface;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class FleetResolverSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        FleetFactoryInterface $fleetFactory,
        FleetJourneyContextInterface $journeyContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
    ): void {
        $this->beConstructedWith(
            $fleetRepository,
            $fleetFactory,
            $journeyContext,
            $resourceAvailabilityChecker,
        );
    }

    public function it_throws_exception_when_cannot_resolve_fleet_but_no_fleet_is_already_stationing_on_planet(
        FleetRepositoryInterface $fleetRepository,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $this->shouldThrow(NoFleetStationingOnPlanetException::class)->during(
            'resolveFromPlanet',
            [new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint]
        );
    }

    public function it_throws_exception_when_not_enough_ships_are_stationing_on_planet(
        FleetRepositoryInterface $fleetRepository,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $stationingFleet,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($stationingFleet);

        $stationingFleet->hasEnoughShips($shipsTakingJourney)
            ->willReturn(false);

        $this->shouldThrow(NotEnoughShipsException::class)->during(
            'resolveFromPlanet',
            [new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint]
        );
    }

    public function it_splits_ships_from_current_fleet_when_resolving_less_ships_that_stationing_fleet_contains(
        FleetRepositoryInterface $fleetRepository,
        FleetJourneyContextInterface $journeyContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        FleetFactoryInterface $fleetFactory,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $stationingFleet,
        ShipsGroupInterface $lightFighterShips,
        ShipsGroupInterface $warshipShips,
        Fleet $resolvedFleet,
        GalaxyPointInterface $startGalaxyPoint,
        ResourcesInterface $fuelRequirements,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($stationingFleet);

        $stationingFleet->hasEnoughShips($shipsTakingJourney)
            ->willReturn(true);

        $stationingFleet->hasMoreShipsThan($shipsTakingJourney)
            ->willReturn(true);

        $fleetSplitResult = [
            $lightFighterShips->getWrappedObject(),
            $warshipShips->getWrappedObject(),
        ];
        $stationingFleet->split($shipsTakingJourney)
            ->willReturn($fleetSplitResult);

        $fleetFactory->create(
            $fleetSplitResult,
            $startGalaxyPoint,
            $resourcesLoad,
        )->willReturn($resolvedFleet);

        $stationingFleet->getStationingGalaxyPoint()
            ->willReturn($startGalaxyPoint);

        $journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $fleetSplitResult,
        )->willReturn($fuelRequirements);

        $resourcesLoad->add($fuelRequirements)->shouldBeCalledOnce();

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourcesLoad)
            ->willReturn(true);

        $resourcesLoad->sum()->willReturn(600);
        $resolvedFleet->getLoadCapacity()->willReturn(600);

        $resolvedFleet->load($resourcesLoad)
            ->shouldBeCalledOnce();

        $this->resolveFromPlanet(new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint)
            ->shouldReturn($resolvedFleet);
    }

    public function it_resolves_fleet_by_taking_all_already_stationing_fleet(
        FleetRepositoryInterface $fleetRepository,
        FleetJourneyContextInterface $journeyContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $stationingFleet,
        GalaxyPointInterface $startGalaxyPoint,
        ResourcesInterface $fuelRequirements,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($stationingFleet);

        $stationingFleet->hasEnoughShips($shipsTakingJourney)
            ->willReturn(true);

        $stationingFleet->hasMoreShipsThan($shipsTakingJourney)
            ->willReturn(false);

        $stationingFleet->getStationingGalaxyPoint()
            ->willReturn($startGalaxyPoint);

        $journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $shipsTakingJourney,
        )->willReturn($fuelRequirements);

        $resourcesLoad->add($fuelRequirements)->shouldBeCalledOnce();

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourcesLoad)
            ->willReturn(true);

        $resourcesLoad->sum()->willReturn(600);
        $stationingFleet->getLoadCapacity()->willReturn(600);

        $stationingFleet->load($resourcesLoad)
            ->shouldBeCalledOnce();

        $this->resolveFromPlanet(new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint)
            ->shouldReturn($stationingFleet);
    }

    public function it_throws_exception_when_there_is_not_enough_resources_for_fleet_load_on_planet_to_start_journey(
        FleetRepositoryInterface $fleetRepository,
        FleetJourneyContextInterface $journeyContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $stationingFleet,
        GalaxyPointInterface $startGalaxyPoint,
        ResourcesInterface $fuelRequirements,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($stationingFleet);

        $stationingFleet->hasEnoughShips($shipsTakingJourney)
            ->willReturn(true);

        $stationingFleet->hasMoreShipsThan($shipsTakingJourney)
            ->willReturn(false);

        $stationingFleet->getStationingGalaxyPoint()
            ->willReturn($startGalaxyPoint);

        $journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $shipsTakingJourney,
        )->willReturn($fuelRequirements);

        $resourcesLoad->add($fuelRequirements)->shouldBeCalledOnce();

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourcesLoad)
            ->willReturn(false);

        $this->shouldThrow(NotEnoughResourcesOnPlanetForFleetLoadException::class)
            ->during('resolveFromPlanet', [
                new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint,
            ]);
    }

    public function it_throws_exception_when_fleet_has_not_enough_load_capacity(
        FleetRepositoryInterface $fleetRepository,
        FleetJourneyContextInterface $journeyContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface $resourcesLoad,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $stationingFleet,
        GalaxyPointInterface $startGalaxyPoint,
        ResourcesInterface $fuelRequirements,
    ): void {
        $planetId = "5fcb0b31-0393-495d-b7da-4bec562864e7";
        $shipsTakingJourney = [
            'light-fighter' => 15,
            'warship' => 1200,
        ];

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($stationingFleet);

        $stationingFleet->hasEnoughShips($shipsTakingJourney)
            ->willReturn(true);

        $stationingFleet->hasMoreShipsThan($shipsTakingJourney)
            ->willReturn(false);

        $stationingFleet->getStationingGalaxyPoint()
            ->willReturn($startGalaxyPoint);

        $journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $shipsTakingJourney,
        )->willReturn($fuelRequirements);

        $resourcesLoad->add($fuelRequirements)->shouldBeCalledOnce();

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourcesLoad)
            ->willReturn(true);

        $resourcesLoad->sum()->willReturn(600);
        $stationingFleet->getLoadCapacity()->willReturn(300);

        $fleetId = "07db1a01-d585-4071-9ee0-2b72b0a471db";
        $stationingFleet->getId()->willReturn(new FleetId($fleetId));

        $this->shouldThrow(NotEnoughFleetLoadCapacityException::class)
            ->during(
                'resolveFromPlanet',
                [
                    new PlanetId($planetId), $shipsTakingJourney, $resourcesLoad, $targetGalaxyPoint]
            );
    }
}
