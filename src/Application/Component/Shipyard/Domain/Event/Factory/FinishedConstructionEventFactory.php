<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Event\Factory;

use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewUnitsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryEntryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\EventInterface;

final class FinishedConstructionEventFactory implements FinishedConstructionEventFactoryInterface
{
    public function createEvent(
        FinishedJobsSummaryEntryInterface $summaryEntry,
        PlanetIdInterface $planetId,
    ): EventInterface {
        switch ($summaryEntry->getUnit()) {
            case ConstructibleUnit::Cannon: {
                return new NewCannonsHaveBeenConstructedEvent(
                    $planetId->getUuid(),
                    $summaryEntry->getType(),
                    $summaryEntry->getQuantity(),
                );
            }
            case ConstructibleUnit::Ship: {
                return new NewShipsHaveBeenConstructedEvent(
                    $planetId->getUuid(),
                    $summaryEntry->getType(),
                    $summaryEntry->getQuantity(),
                );
            }
        }

        return new NewUnitsHaveBeenConstructedEvent(
            $planetId->getUuid(),
            $summaryEntry->getUnit()->value,
            $summaryEntry->getType(),
            $summaryEntry->getQuantity(),
        );
    }
}
