<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenCancelled;
use App\Component\Resource\Application\Command\DecreaseMiningSpeedCommand;
use App\Component\SharedKernel\CommandBusInterface;
use App\Component\SharedKernel\EventSubscriberInterface;

final class DecreaseMiningSpeedChangeSubscriber implements EventSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(BuildingUpgradeHasBeenCancelled $event): void
    {
        $this->commandBus->dispatch(new DecreaseMiningSpeedCommand(
            $event->getBuildingCode()
        ));
    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenCancelled::class;
    }
}