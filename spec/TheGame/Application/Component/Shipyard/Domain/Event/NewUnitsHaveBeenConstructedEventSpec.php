<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use PhpSpec\ObjectBehavior;

final class NewUnitsHaveBeenConstructedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "d7a1a33e-2669-485e-8867-6e129761359c";
        $unit = 'unknown-unit';
        $constructionType = 'unknown-type';
        $quantity = 500;

        $this->beConstructedWith(
            $planetId,
            $unit,
            $constructionType,
            $quantity
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("d7a1a33e-2669-485e-8867-6e129761359c");
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
