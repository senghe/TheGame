<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Planet\Domain\Event\BuildingUpgradeHasBeenCancelled;
use App\Component\SharedKernel\EventSubscriberInterface;

final class DeleteMiningSpeedEventSubscriber implements EventSubscriberInterface
{
    public function handle(BuildingUpgradeHasBeenCancelled $event): void
    {

    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenCancelled::class;
    }
}