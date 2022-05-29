<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\Resource\Application\Command\IncreaseMiningSpeedCommand;
use App\Component\SharedKernel\CommandBusInterface;
use App\Component\SharedKernel\EventSubscriberInterface;

final class IncreaseMiningSpeedChangeSubscriber implements EventSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(BuildingUpgradeHasBeenStarted $event): void
    {
        $miningSpeeds = $event->getMiningSpeeds();

        foreach ($miningSpeeds as $resourceCode => $speed) {
            $this->commandBus->dispatch(new IncreaseMiningSpeedCommand(
                $resourceCode, $speed
            ));
        }
    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenStarted::class;
    }
}