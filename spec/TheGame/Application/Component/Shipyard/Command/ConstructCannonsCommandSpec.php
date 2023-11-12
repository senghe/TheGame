<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Command;

use PhpSpec\ObjectBehavior;

final class ConstructCannonsCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "E4330710-2AC5-4C1F-86B5-9332CEA8F91B";
        $cannonType = 'laser';
        $quantity = 500;

        $this->beConstructedWith($shipyardId, $cannonType, $quantity);
    }

    public function it_has_shipyard_id(): void
    {
        $this->getShipyardId()->shouldReturn("E4330710-2AC5-4C1F-86B5-9332CEA8F91B");
    }

    public function it_has_cannon_type(): void
    {
        $this->getCannonType()->shouldReturn('laser');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()->shouldReturn(500);
    }
}
