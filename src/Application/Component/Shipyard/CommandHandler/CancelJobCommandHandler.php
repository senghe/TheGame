<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Shipyard\Command\CancelJobCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\JobHasBeenCancelledEvent;
use TheGame\Application\Component\Shipyard\Domain\JobId;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class CancelJobCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(CancelJobCommand $command): void
    {
        $shipyardId = new ShipyardId($command->getShipyardId());
        $shipyard = $this->shipyardRepository->findAggregate($shipyardId);
        if ($shipyard === null) {
            throw new ShipyardHasNotBeenFoundException($shipyardId);
        }

        $jobId = new JobId($command->getJobId());
        $shipyard->cancelJob($jobId);

        $resourceRequirements = $shipyard->getResourceRequirements($jobId);

        $event = new JobHasBeenCancelledEvent(
            $command->getShipyardId(),
            $command->getJobId(),
            $shipyard->getPlanetId()->getUuid(),
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
