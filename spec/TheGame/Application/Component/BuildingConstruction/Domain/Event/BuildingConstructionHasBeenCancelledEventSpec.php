<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class BuildingConstructionHasBeenCancelledEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $resourceRequirements = [
            "6842FCCF-9905-41FF-B542-4788579F0847" => 750,
        ];

        $this->beConstructedWith(
            $planetId, BuildingType::ResourceStorage->value, $resourceRequirements,
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

    public function it_has_resource_requirements(): void
    {
        $this->getResourceRequirements()->shouldReturn([
            "6842FCCF-9905-41FF-B542-4788579F0847" => 750,
        ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_string_keys(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingType = BuildingType::ResourceStorage->value;
        $resourceRequirements = [
            300 => 750,
        ];

        $this->beConstructedWith(
            $planetId, $buildingType, $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $planetId, $buildingType, $resourceRequirements,
            ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_integer_value(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingType = BuildingType::ResourceStorage->value;
        $resourceRequirements = [
            "6842FCCF-9905-41FF-B542-4788579F0847" => "not-int-value",
        ];

        $this->beConstructedWith(
            $planetId, $buildingType, $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $planetId, $buildingType, $resourceRequirements,
            ]);
    }
}
