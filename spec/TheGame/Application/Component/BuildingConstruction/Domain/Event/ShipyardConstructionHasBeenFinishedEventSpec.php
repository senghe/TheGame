<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class ShipyardConstructionHasBeenFinishedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingId = "95EBB0A5-95C6-4D7F-AD9C-475DE30D59D9";
        $level = 1;

        $this->beConstructedWith(
            $planetId,
            $buildingId,
            $level,
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D");
    }

    public function it_has_building_id(): void
    {
        $this->getBuildingId()->shouldReturn("95EBB0A5-95C6-4D7F-AD9C-475DE30D59D9");
    }

    public function it_has_building_type(): void
    {
        $this->getBuildingType()->shouldReturn(BuildingType::Shipyard->value);
    }

    public function it_has_upgraded_level(): void
    {
        $this->getUpgradedLevel()->shouldReturn(1);
    }
}
