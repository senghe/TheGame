<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\EventListener;

use TheGame\Application\Component\BuildingConstruction\Domain\Event\ShipyardConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;

final class UpgradeShipyardEventListener
{
    public function __construct(ShipyardRepositoryInterface $shipyardRepository)
    {

    }

    public function __invoke(ShipyardConstructionHasBeenFinishedEvent $event): void
    {

    }
}
