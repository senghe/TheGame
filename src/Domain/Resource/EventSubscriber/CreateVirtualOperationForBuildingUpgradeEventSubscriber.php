<?php

declare(strict_types=1);

namespace App\Domain\Resource\EventSubscriber;

use App\Domain\Planet\Event\BuildingUpgradeHasBeenStarted;
use App\Domain\SharedKernel\EventSubscriberInterface;

final class CreateVirtualOperationForBuildingUpgradeEventSubscriber implements EventSubscriberInterface
{
    public function handle(BuildingUpgradeHasBeenStarted $event): void
    {

    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenStarted::class;
    }
}