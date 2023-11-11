<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use PhpSpec\ObjectBehavior;

final class NewUnitsHaveBeenConstructedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $unit = 'unknown-unit';
        $constructionType = 'unknown-type';
        $quantity = 500;

        $this->beConstructedWith(
            $unit,
            $constructionType,
            $quantity
        );
    }

    public function it_has_construction_unit(): void
    {
        $this->getUnit()->shouldReturn('unknown-unit');
    }

    public function it_has_construction_type(): void
    {
        $this->getType()->shouldReturn('unknown-type');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()->shouldReturn(500);
    }
}
