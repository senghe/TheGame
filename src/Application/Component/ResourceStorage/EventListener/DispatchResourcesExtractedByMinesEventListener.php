<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\ResourceMines\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class DispatchResourcesExtractedByMinesEventListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ResourceHasBeenExtractedEvent $event): void
    {
        $command = new DispatchResourcesCommand(
            $event->getPlanetId(),
            $event->getResourceId(),
            $event->getAmount(),
        );
        $this->commandBus->dispatch($command);
    }
}
