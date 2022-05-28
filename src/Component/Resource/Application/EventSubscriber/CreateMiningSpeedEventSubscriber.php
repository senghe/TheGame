<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\EventSubscriber;

use App\Component\Planet\Domain\Event\BuildingUpgradeHasBeenStarted;
use App\Component\SharedKernel\EventSubscriberInterface;

final class CreateMiningSpeedEventSubscriber implements EventSubscriberInterface
{
    public function handle(BuildingUpgradeHasBeenStarted $event): void
    {

    }

    public function getSubscribedEvent(): string
    {
        return BuildingUpgradeHasBeenStarted::class;
    }
}