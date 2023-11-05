<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class ResourceStorageConstructionHasBeenFinishedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $level = 1;
        $resourceContextId = "F8214FC4-B5E8-4D19-8360-8E95BE8FBF9B";

        $this->beConstructedWith(
            $planetId,
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
        $this->getBuildingType()->shouldReturn(BuildingType::ResourceStorage->value);
    }

    public function it_has_current_level(): void
    {
        $this->getLevel()->shouldReturn(1);
    }

    public function it_has_resource_context_id(): void
    {
        $this->getResourceContextId()->shouldReturn("F8214FC4-B5E8-4D19-8360-8E95BE8FBF9B");
    }
}
