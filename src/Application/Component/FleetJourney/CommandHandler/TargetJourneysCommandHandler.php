<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\FleetJourney\Command\TargetJourneysCommand;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\UserId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class TargetJourneysCommandHandler
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(TargetJourneysCommand $command): void
    {
        $userId = new UserId($command->getUserId());
        $fleets = $this->fleetRepository->findInJourneyForUser($userId);
        foreach ($fleets as $fleet) {
            $mission = $fleet->getJourneyMissionType();
            $fleet->tryToReachJourneyTargetPoint();
            if ($fleet->didReachJourneyTargetPoint() === false) {
                continue;
            }

            $this->eventBus->dispatch(
                new FleetHasReachedJourneyTargetPointEvent(
                    $mission->value,
                    $fleet->getId()->getUuid(),
                    $fleet->getJourneyTargetPoint()->format(),
                    $fleet->getResourcesLoad(),
                ),
            );
        }
    }
}
