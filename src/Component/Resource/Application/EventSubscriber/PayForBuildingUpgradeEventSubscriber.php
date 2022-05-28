<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Planet\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\SharedKernel\EventSubscriberInterface;

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