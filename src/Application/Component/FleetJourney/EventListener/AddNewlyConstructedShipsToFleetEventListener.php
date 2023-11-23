<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\EventListener;

use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;

final class AddNewlyConstructedShipsToFleetEventListener
{
    public function __invoke(NewShipsHaveBeenConstructedEvent $event): void
    {
    }
}
