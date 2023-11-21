<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\FleetJourney\Command\ReturnJourneysCommand;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyReturnPointEvent;
use TheGame\Application\Component\FleetJourney\FleetRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\UserId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ReturnJourneysCommandHandler
{
    public function __construct(
        private readonly FleetRepositoryInterface $fleetRepository,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(ReturnJourneysCommand $command): void
    {
        $userId = new UserId($command->getUserId());
        $fleets = $this->fleetRepository->findFlyingBackFromJourneyForUser($userId);
        foreach ($fleets as $fleet) {
            $fleet->reachJourneyReturnPoint();
            if ($fleet->didReturnFromJourney() === false) {
                continue;
            }

            $this->eventBus->dispatch(
                new FleetHasReachedJourneyReturnPointEvent(
                    $fleet->getId()->getUuid(),
                    $fleet->getJourneyStartPoint()->format(),
                    $fleet->getJourneyTargetPoint()->format(),
                    $fleet->getResourcesLoad(),
                ),
            );
        }
    }
}
