<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\EventListener;

use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Domain\Factory\FleetFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroup;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\Resources;

final class AddNewlyConstructedShipsToFleetEventListener
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly FleetFactoryInterface $fleetFactory,
        private readonly NavigatorInterface $navigator,
        private readonly FleetJourneyContextInterface $fleetJourneyContext,
    ) {
    }

    public function __invoke(NewShipsHaveBeenConstructedEvent $event): void
    {
        $planetId = new PlanetId($event->getPlanetId());
        $fleetCurrentlyStationingOnPlanet = $this->fleetRepository->findStationingOnPlanet($planetId);
        if ($fleetCurrentlyStationingOnPlanet === null) {
            $fleetCurrentlyStationingOnPlanet = $this->fleetFactory->create(
                [],
                $this->navigator->getPlanetPosition($planetId),
                new Resources(),
            );
        }

        $shipName = $event->getName();
        $fleetCurrentlyStationingOnPlanet->addShips([
            new ShipsGroup(
                $shipName,
                $event->getQuantity(),
                $this->fleetJourneyContext->getShipBaseSpeed($shipName),
                $this->fleetJourneyContext->getShipLoadCapacity($shipName),
            ),
        ]);
    }
}
