<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\BuildingType;

final class BuildingConstructionHasBeenStartedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingId = "6cdc9176-2dfa-4d36-8e8a-264f9cd0d7bb";
        $newLevel = 50;
        $resourceRequirements = [
            "6842FCCF-9905-41FF-B542-4788579F0847" => 750,
        ];

        $this->beConstructedWith(
            $planetId,
            BuildingType::ResourceStorage->value,
            $buildingId,
            $newLevel,
            $resourceRequirements,
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

    public function it_has_new_level(): void
    {
        $this->getNewLevel()->shouldReturn(50);
    }

    public function it_has_building_id(): void
    {
        $this->getBuildingId()->shouldReturn("6cdc9176-2dfa-4d36-8e8a-264f9cd0d7bb");
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
        $buildingId = "6cdc9176-2dfa-4d36-8e8a-264f9cd0d7bb";
        $newLevel = 50;
        $resourceRequirements = [
            300 => 750,
        ];

        $this->beConstructedWith(
            $planetId,
            $buildingType,
            $buildingId,
            $newLevel,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $planetId, $buildingType, $buildingId, $newLevel, $resourceRequirements,
            ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_integer_value(): void
    {
        $planetId = "6B685A5A-E279-4E8D-A9D4-0EEC6E7F0F3D";
        $buildingType = BuildingType::ResourceStorage->value;
        $buildingId = "6cdc9176-2dfa-4d36-8e8a-264f9cd0d7bb";
        $newLevel = 50;
        $resourceRequirements = [
            "6842FCCF-9905-41FF-B542-4788579F0847" => "not-int-value",
        ];

        $this->beConstructedWith(
            $planetId,
            $buildingType,
            $buildingId,
            $newLevel,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $planetId, $buildingType, $buildingId, $newLevel, $resourceRequirements,
            ]);
    }
}
