<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use PhpSpec\ObjectBehavior;

final class NewCannonsHaveBeenConstructedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $cannonType = 'laser';
        $quantity = 500;

        $this->beConstructedWith($cannonType, $quantity);
    }

    public function it_has_cannon_type(): void
    {
        $this->getType()
            ->shouldReturn('laser');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()
            ->shouldReturn(500);
    }
}
