<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Command;

use PhpSpec\ObjectBehavior;

final class ConstructShipsCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "E4330710-2AC5-4C1F-86B5-9332CEA8F91B";
        $shipType = 'light-fighter';
        $quantity = 500;

        $this->beConstructedWith($shipyardId, $shipType, $quantity);
    }

    public function it_has_shipyard_id(): void
    {
        $this->getShipyardId()->shouldReturn("E4330710-2AC5-4C1F-86B5-9332CEA8F91B");
    }

    public function it_has_ship_type(): void
    {
        $this->getShipType()->shouldReturn('light-fighter');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()->shouldReturn(500);
    }
}
