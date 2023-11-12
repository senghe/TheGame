<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use PhpSpec\ObjectBehavior;

final class NewShipsHaveBeenConstructedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $cannonType = 'light-fighter';
        $quantity = 500;

        $this->beConstructedWith($cannonType, $quantity);
    }

    public function it_has_ship_type(): void
    {
        $this->getType()
            ->shouldReturn('light-fighter');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()
            ->shouldReturn(500);
    }
}
