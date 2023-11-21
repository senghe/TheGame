<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\FleetJourney\Command\CancelJourneyCommand;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasCancelledJourneyEvent;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class CancelJourneyCommandHandler
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(CancelJourneyCommand $command): void
    {
        $fleetId = new FleetId($command->getFleetId());
        $fleet = $this->fleetRepository->find($fleetId);

        if ($fleet === null) {
            throw new InconsistentModelException(sprintf("Fleet %d doesn't exist", $command->getFleetId()));
        }

        $fleet->cancelJourney();

        $this->eventBus->dispatch(
            new FleetHasCancelledJourneyEvent(
                $command->getFleetId(),
                $fleet->getJourneyTargetPoint()->format(),
                $fleet->getResourcesLoad(),
            ),
        );
    }
}
