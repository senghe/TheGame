<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event\Factory;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\Event\NewCannonsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\Event\NewShipsHaveBeenConstructedEvent;
use TheGame\Application\Component\Shipyard\Domain\FinishedJobsSummaryEntryInterface;

final class FinishedConstructionEventFactorySpec extends ObjectBehavior
{
    public function it_returns_finish_construction_event_for_ship_unit(
        FinishedJobsSummaryEntryInterface $summaryEntry,
    ): void {
        $summaryEntry->getUnit()->willReturn(ConstructibleUnit::Ship);
        $summaryEntry->getType()->willReturn('light-fighter');
        $summaryEntry->getQuantity()->willReturn(50);

        $this->createEvent($summaryEntry)
            ->shouldReturnAnInstanceOf(NewShipsHaveBeenConstructedEvent::class);
    }

    public function it_returns_finish_construction_event_for_cannon_unit(
        FinishedJobsSummaryEntryInterface $summaryEntry,
    ): void {
        $summaryEntry->getUnit()->willReturn(ConstructibleUnit::Cannon);
        $summaryEntry->getType()->willReturn('laser');
        $summaryEntry->getQuantity()->willReturn(50);

        $this->createEvent($summaryEntry)
            ->shouldReturnAnInstanceOf(NewCannonsHaveBeenConstructedEvent::class);
    }

    public function it_returns_default_finish_construction_event_for_unit_which_is_not_supported(): void
    {
        throw new SkippingException('Cannot spec this scenario, because of using enum');
    }
}
