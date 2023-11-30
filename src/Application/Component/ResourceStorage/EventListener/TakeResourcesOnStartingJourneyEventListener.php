<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasStartedJourneyEvent;
use TheGame\Application\Component\ResourceStorage\Command\UseResourceCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class TakeResourcesOnStartingJourneyEventListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(FleetHasStartedJourneyEvent $event): void
    {
        $resourcesPivot = [];
        foreach ($event->getResourcesLoad() as $resourceId => $amount) {
            $resourcesPivot[$resourceId] = ($resourcesPivot[$resourceId] ?? 0) + $amount;
        }

        foreach ($resourcesPivot as $resourceId => $amount) {
            $command = new UseResourceCommand(
                $event->getPlanetId(),
                $resourceId,
                $amount,
            );
            $this->commandBus->dispatch($command);
        }
    }
}
