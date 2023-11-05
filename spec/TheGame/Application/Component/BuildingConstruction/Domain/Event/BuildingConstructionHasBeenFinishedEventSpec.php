<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class BuildingConstructionHasBeenFinishedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $level = 1;

        $this->beConstructedWith(
            $planetId,
            BuildingType::ResourceStorage->value,
            $level,
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D");
    }

    public function it_has_building_type(): void
    {
        $this->getBuildingType()->shouldReturn(BuildingType::ResourceStorage->value);
    }

    public function it_has_level(): void
    {
        $this->getLevel()->shouldReturn(1);
    }
}
