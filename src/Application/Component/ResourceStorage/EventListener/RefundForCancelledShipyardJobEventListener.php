<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\JobHasBeenCancelledEvent;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class RefundForCancelledShipyardJobEventListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(JobHasBeenCancelledEvent $event): void
    {
        foreach ($event->getResourceRequirements() as $resourceId => $amount) {
            $command = new DispatchResourcesCommand(
                $event->getPlanetId(),
                $resourceId,
                $amount,
            );
            $this->commandBus->dispatch($command);
        }
    }
}
