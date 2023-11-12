<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\Component\Shipyard\Command\ConstructShipsCommand;
use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenQueuedEvent;
use TheGame\Application\Component\Shipyard\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\Shipyard\Domain\Factory\JobFactoryInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ConstructShipsCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly JobFactoryInterface $jobFactory,
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

        $shipType = $command->getShipType();
        $quantity = $command->getQuantity();
        $job = $this->createJob($shipType, $quantity, $shipyard);

        $planetId = $shipyard->getPlanetId();

        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $job->getRequirements(),
        );
        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $shipType);
        }

        $shipyard->queueJob($job);

        $event = new NewShipsHaveBeenQueuedEvent(
            $shipType,
            $quantity,
            $shipyard->getPlanetId()->getUuid(),
            $job->getRequirements()->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }

    private function createJob(
        string $shipType,
        int $quantity,
        Shipyard $shipyard,
    ): Job {
        return $this->jobFactory->createNewShipsJob(
            $shipType,
            $quantity,
            $shipyard->getCurrentLevel(),
            $this->shipyardBalanceContext->getShipConstructionTime($shipType, $shipyard->getCurrentLevel()),
            $this->shipyardBalanceContext->getShipProductionLoad($shipType),
            $this->shipyardBalanceContext->getShipResourceRequirements($shipType),
        );
    }
}
