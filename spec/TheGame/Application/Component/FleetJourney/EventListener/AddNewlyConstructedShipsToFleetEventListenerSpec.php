<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\EventListener;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Factory\FleetFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroup;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\Resources;

final class AddNewlyConstructedShipsToFleetEventListenerSpec extends ObjectBehavior
{
    public function let(
        FleetRepositoryInterface $fleetRepository,
        FleetFactoryInterface $fleetFactory,
        NavigatorInterface $navigator,
        FleetJourneyContextInterface $fleetJourneyContext,
    ): void {
        $this->beConstructedWith(
            $fleetRepository,
            $fleetFactory,
            $navigator,
            $fleetJourneyContext,
        );
    }

    public function it_adds_group_of_ships_to_the_fleet(
        FleetRepositoryInterface $fleetRepository,
        FleetJourneyContextInterface $fleetJourneyContext,
        Fleet $fleet,
    ): void {
        $planetId = "a0fb9362-cbd3-4488-a65f-f835d318e2fc";
        $shipType = "light-fighter";
        $quantity = 10;

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn($fleet);

        $shipBaseSpeed = 10;
        $fleetJourneyContext->getShipBaseSpeed($shipType)->willReturn($shipBaseSpeed);
        $shipCapacityLoad = 300;
        $fleetJourneyContext->getShipLoadCapacity($shipType)->willReturn($shipCapacityLoad);

        $shipGroup = new ShipsGroup($shipType, $quantity, $shipBaseSpeed, $shipCapacityLoad);
        $fleet->addShips([$shipGroup])->shouldBeCalledOnce();

        $event = new NewShipsHaveBeenConstructedEvent($planetId, $shipType, $quantity);
        $this->__invoke($event);
    }

    public function it_creates_a_fleet_and_adds_group_of_ships_to_them(
        FleetRepositoryInterface $fleetRepository,
        FleetFactoryInterface $fleetFactory,
        NavigatorInterface $navigator,
        FleetJourneyContextInterface $fleetJourneyContext,
        Fleet $fleet,
        GalaxyPointInterface $planetGalaxyPoint,
    ): void {
        $planetId = "a0fb9362-cbd3-4488-a65f-f835d318e2fc";
        $shipType = "light-fighter";
        $quantity = 10;

        $fleetRepository->findStationingOnPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $navigator->getPlanetPoint(new PlanetId($planetId))
            ->willReturn($planetGalaxyPoint);

        $fleetFactory->create(
            [],
            $planetGalaxyPoint,
            new Resources(),
        )->willReturn($fleet);

        $shipBaseSpeed = 10;
        $fleetJourneyContext->getShipBaseSpeed($shipType)->willReturn($shipBaseSpeed);
        $shipCapacityLoad = 300;
        $fleetJourneyContext->getShipLoadCapacity($shipType)->willReturn($shipCapacityLoad);

        $shipGroup = new ShipsGroup($shipType, $quantity, $shipBaseSpeed, $shipCapacityLoad);
        $fleet->addShips([$shipGroup])->shouldBeCalledOnce();

        $event = new NewShipsHaveBeenConstructedEvent($planetId, $shipType, $quantity);
        $this->__invoke($event);
    }
}
