<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\Component\Shipyard\Command\ConstructCannonsCommand;
use TheGame\Application\Component\Shipyard\Domain\Entity\Job;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenQueuedEvent;
use TheGame\Application\Component\Shipyard\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\Shipyard\Domain\Factory\JobFactoryInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ConstructCannonsCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly JobFactoryInterface $jobFactory,
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

        $cannonType = $command->getCannonType();
        $quantity = $command->getQuantity();
        $job = $this->createJob($cannonType, $quantity, $shipyard);

        $planetId = $shipyard->getPlanetId();
        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $job->getRequirements(),
        );
        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $cannonType);
        }

        $shipyard->queueJob($job);

        $event = new NewCannonsHaveBeenQueuedEvent(
            $cannonType,
            $quantity,
            $shipyard->getPlanetId()->getUuid(),
            $job->getRequirements()->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }

    private function createJob(
        string $cannonType,
        int $quantity,
        Shipyard $shipyard,
    ): Job {
        return $this->jobFactory->createNewCannonsJob(
            $cannonType,
            $quantity,
            $shipyard->getCurrentLevel(),
            $this->shipyardBalanceContext->getCannonConstructionTime($cannonType, $shipyard->getCurrentLevel()),
            $this->shipyardBalanceContext->getCannonProductionLoad($cannonType),
            $this->shipyardBalanceContext->getCannonResourceRequirements($cannonType),
        );
    }
}
