<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\ResourceStorage\Command\UseResourceCommand;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenQueuedEvent;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class PayForQueuedShipsEventListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(NewShipsHaveBeenQueuedEvent $event): void
    {
        foreach ($event->getResourceRequirements() as $resourceId => $amount) {
            $command = new UseResourceCommand(
                $event->getPlanetId(),
                $resourceId,
                $amount,
            );
            $this->commandBus->dispatch($command);
        }
    }
}
