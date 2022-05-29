<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Building\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\Resource\Application\Command\ChangeStorageAmountCommand;
use App\Component\SharedKernel\CommandBusInterface;
use App\Component\SharedKernel\EventSubscriberInterface;

final class RefundBuildingUpdateEventSubscriber implements EventSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(BuildingUpgradeHasBeenStarted $event): void
    {
        foreach ($event->getResourceAmounts() as $resourceCode => $amount) {
            $this->commandBus->dispatch(new ChangeStorageAmountCommand(
                $resourceCode, -$amount
            ));
        }
    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenStarted::class;
    }
}