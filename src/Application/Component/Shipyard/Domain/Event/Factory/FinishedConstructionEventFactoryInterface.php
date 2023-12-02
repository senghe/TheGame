<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event\Factory;

use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryEntryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\EventInterface;

interface FinishedConstructionEventFactoryInterface
{
    public function createEvent(
        FinishedJobsSummaryEntryInterface $summaryEntry,
        PlanetIdInterface $planetId,
    ): EventInterface;
}
