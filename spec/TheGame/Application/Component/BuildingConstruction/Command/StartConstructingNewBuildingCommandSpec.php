<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Command;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class StartConstructingNewBuildingCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "05FEB5A6-285B-46A8-8A3D-10280C68ECBA";
        $buildingType = BuildingType::ResourceStorage->value;
        $resourceContextId = "68A3E097-C576-4087-9655-1ECAB8AA92F6";

        $this->beConstructedWith(
            $planetId,
            $buildingType,
            $resourceContextId,
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("05FEB5A6-285B-46A8-8A3D-10280C68ECBA");
    }

    public function it_has_building_type(): void
    {
        $this->getBuildingType()->shouldReturn(BuildingType::ResourceStorage->value);
    }

    public function it_has_resource_context_id(): void
    {
        $this->getResourceContextId()->shouldReturn("68A3E097-C576-4087-9655-1ECAB8AA92F6");
    }

    public function it_has_no_resource_context_id(): void
    {
        $planetId = "05FEB5A6-285B-46A8-8A3D-10280C68ECBA";
        $buildingType = BuildingType::ResourceStorage->value;

        $this->beConstructedWith(
            $planetId,
            $buildingType,
            null,
        );

        $this->getResourceContextId()->shouldReturn(null);
    }
}
