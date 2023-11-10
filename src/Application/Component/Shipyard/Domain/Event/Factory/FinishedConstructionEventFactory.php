<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event\Factory;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewUnitsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryEntry;
use TheGame\Application\SharedKernel\EventInterface;

final class FinishedConstructionEventFactory
{
    public function createEvent(FinishedJobsSummaryEntry $summaryEntry): EventInterface
    {
        switch ($summaryEntry->getUnit()) {
            case ConstructibleUnit::Cannon: {
                return new NewCannonsHaveBeenConstructedEvent(
                    $summaryEntry->getType(),
                    $summaryEntry->getQuantity(),
                );
            }
            case ConstructibleUnit::Ship: {
                return new NewShipsHaveBeenConstructedEvent(
                    $summaryEntry->getType(),
                    $summaryEntry->getQuantity(),
                );
            }
        }

        return new NewUnitsHaveBeenConstructedEvent(
            $summaryEntry->getUnit()->value,
            $summaryEntry->getType(),
            $summaryEntry->getQuantity(),
        );
    }
}
