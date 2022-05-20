<?php

declare(strict_types=1);

namespace App\Domain\Resource\EventSubscriber;

use App\Domain\Planet\Event\BuildingUpgradeHasBeenStarted;
use App\Domain\SharedKernel\EventSubscriberInterface;

final class PayForBuildingUpgradeEventSubscriber implements EventSubscriberInterface
{
    public function handle(BuildingUpgradeHasBeenStarted $event): void
    {
        foreach ($event->getResourceAmounts() as $resourceCode => $amount) {

        }
    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenStarted::class;
    }
}