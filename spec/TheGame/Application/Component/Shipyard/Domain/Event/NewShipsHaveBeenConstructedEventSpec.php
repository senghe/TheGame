<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use PhpSpec\ObjectBehavior;

final class NewShipsHaveBeenConstructedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "d7a1a33e-2669-485e-8867-6e129761359c";
        $shipType = 'light-fighter';
        $quantity = 500;

        $this->beConstructedWith($planetId, $shipType, $quantity);
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("d7a1a33e-2669-485e-8867-6e129761359c");
    }

    public function it_has_ship_type(): void
    {
        $this->getName()
            ->shouldReturn('light-fighter');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()
            ->shouldReturn(500);
    }
}
