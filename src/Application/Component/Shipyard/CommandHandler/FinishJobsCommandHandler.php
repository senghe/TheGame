<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\CommandHandler;

use TheGame\Application\Component\Shipyard\Command\FinishJobsCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\Factory\FinishedConstructionEventFactory;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class FinishJobsCommandHandler
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly FinishedConstructionEventFactory $finishedConstructionEventFactory,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(FinishJobsCommand $command): void
    {
        $shipyardId = new ShipyardId($command->getShipyardId());
        $shipyard = $this->shipyardRepository->findAggregate($shipyardId);
        if ($shipyard === null) {
            throw new ShipyardHasNotBeenFoundException($shipyardId);
        }

        $summary = $shipyard->finishJobs();
        foreach ($summary as $entry) {
            $event = $this->finishedConstructionEventFactory->createEvent($entry);
            $this->eventBus->dispatch($event);
        }
    }
}
