<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\Shipyard\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\Component\Shipyard\Command\ConstructCannonsCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenQueuedEvent;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Cannon;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ConstructCannonsCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly ShipyardContextInterface $shipyardBalanceContext,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ConstructCannonsCommand $command): void
    {
        $shipyardId = new ShipyardId($command->getShipyardId());
        $shipyard = $this->shipyardRepository->findAggregate($shipyardId);
        if ($shipyard === null) {
            throw new ShipyardHasNotBeenFoundException($shipyardId);
        }

        $cannon = new Cannon(
            $command->getType(),
            $this->shipyardBalanceContext->getCannonResourceRequirements($command->getType()),
            $this->shipyardBalanceContext->getCannonConstructionTime(
                $command->getType(),
                $shipyard->getCurrentLevel(),
            ),
            $this->shipyardBalanceContext->getCannonProductionLoad($command->getType()),
        );

        $planetId = $shipyard->getPlanetId();
        $resourceRequirements = $shipyard->calculateResourceRequirements($cannon, $command->getQuantity());
        $hasEnoughResources = $this->resourceAvailabilityChecker->check($planetId, $resourceRequirements);
        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $cannon->getType());
        }

        $shipyard->queueCannons($cannon, $command->getQuantity());

        $event = new NewCannonsHaveBeenQueuedEvent(
            $command->getType(),
            $command->getQuantity(),
            $shipyard->getPlanetId()->getUuid(),
            $resourceRequirements->toScalarArray(),

        );
        $this->eventBus->dispatch($event);
    }
}
