<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenCancelled;
use App\Component\Resource\Application\Command\DecreaseMiningSpeedCommand;
use App\SharedKernel\Port\CommandBusInterface;
use App\SharedKernel\Port\EventSubscriberInterface;

final class DecreaseMiningSpeedChangeSubscriber implements EventSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(BuildingUpgradeHasBeenCancelled $event): void
    {
        if ($event->isMine() === false) {
            return;
        }

        $this->commandBus->dispatch(new DecreaseMiningSpeedCommand(
            $event->getPlanetId()
        ));
    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenCancelled::class;
    }
}