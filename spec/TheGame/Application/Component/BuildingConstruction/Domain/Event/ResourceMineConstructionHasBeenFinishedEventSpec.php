<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class ResourceMineConstructionHasBeenFinishedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingId = "22AF7B4B-5F96-440A-BAC4-EAA2BBA82E4C";
        $resourceContextId = "F8214FC4-B5E8-4D19-8360-8E95BE8FBF9B";
        $level = 1;

        $this->beConstructedWith(
            $planetId,
            $buildingId,
            $resourceContextId,
            $level,
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D");
    }

    public function it_has_building_type(): void
    {
        $this->getBuildingType()->shouldReturn(BuildingType::ResourceMine->value);
    }

    public function it_has_building_id(): void
    {
        $this->getBuildingId()->shouldReturn("22AF7B4B-5F96-440A-BAC4-EAA2BBA82E4C");
    }

    public function it_has_upgraded_level(): void
    {
        $this->getUpgradedLevel()->shouldReturn(1);
    }

    public function it_has_resource_context_id(): void
    {
        $this->getResourceContextId()->shouldReturn("F8214FC4-B5E8-4D19-8360-8E95BE8FBF9B");
    }
}
