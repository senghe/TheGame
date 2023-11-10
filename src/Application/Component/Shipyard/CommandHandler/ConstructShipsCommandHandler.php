<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\Component\Shipyard\Command\ConstructShipsCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenQueuedEvent;
use TheGame\Application\Component\Shipyard\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Ship;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ConstructShipsCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly ShipyardContextInterface $shipyardBalanceContext,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ConstructShipsCommand $command): void
    {
        $shipyardId = new ShipyardId($command->getShipyardId());
        $shipyard = $this->shipyardRepository->findAggregate($shipyardId);
        if ($shipyard === null) {
            throw new ShipyardHasNotBeenFoundException($shipyardId);
        }

        $ship = new Ship(
            $command->getType(),
            $this->shipyardBalanceContext->getShipResourceRequirements($command->getType()),
            $this->shipyardBalanceContext->getShipConstructionTime(
                $command->getType(),
                $shipyard->getCurrentLevel(),
            ),
            $this->shipyardBalanceContext->getShipProductionLoad($command->getType()),
        );

        $planetId = $shipyard->getPlanetId();

        $resourceRequirements = $shipyard->calculateResourceRequirements($ship, $command->getQuantity());
        $hasEnoughResources = $this->resourceAvailabilityChecker->check($planetId, $resourceRequirements);
        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $ship->getType());
        }

        $shipyard->queueShips($ship, $command->getQuantity());

        $event = new NewShipsHaveBeenQueuedEvent(
            $command->getType(),
            $command->getQuantity(),
            $shipyard->getPlanetId()->getUuid(),
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
