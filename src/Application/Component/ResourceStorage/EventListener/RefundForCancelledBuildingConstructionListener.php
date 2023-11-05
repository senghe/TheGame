<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;

final class RefundForCancelledBuildingConstructionListener
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(BuildingConstructionHasBeenCancelledEvent $event): void
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
