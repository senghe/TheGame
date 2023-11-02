<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\ResourceStorage\Command\UseResourceCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class PayForConstructedBuildingListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(BuildingConstructionHasBeenStartedEvent $event): void
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
