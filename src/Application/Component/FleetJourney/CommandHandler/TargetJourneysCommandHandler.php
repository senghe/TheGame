<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\FleetJourney\Command\TargetJourneysCommand;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerId;
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
        $playerId = new PlayerId($command->getPlayerId());
        $fleets = $this->fleetRepository->findInJourneyForPlayer($playerId);
        foreach ($fleets as $fleet) {
            $fleet->tryToReachJourneyTargetPoint();
            if ($fleet->didReachJourneyTargetPoint() === false) {
                continue;
            }

            $mission = $fleet->getJourneyMissionType();
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
