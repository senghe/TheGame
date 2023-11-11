<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event\Factory;

use PhpSpec\ObjectBehavior;

final class FinishedConstructionEventFactorySpec extends ObjectBehavior
{
    public function it_returns_finish_construction_event_for_ship_unit(): void
    {
    }

    public function it_returns_finish_construction_event_for_cannon_unit(): void
    {
    }

    public function it_returns_default_finish_construction_event_for_unit_which_is_not_supported(): void
    {
    }
}
