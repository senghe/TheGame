<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class FleetHasCancelledJourneyEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $fleetId = "e29a0a69-380d-4871-8daa-ed8e42696fba";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            "e0c85daa-2b52-466d-9c7d-34063c0646d9" => 500,
        ];

        $this->beConstructedWith($fleetId, $targetGalaxyPoint, $resourcesLoad);
    }

    public function it_has_fleet_id(): void
    {
        $this->getFleetId()->shouldReturn("e29a0a69-380d-4871-8daa-ed8e42696fba");
    }

    public function it_has_target_galaxy_point(): void
    {
        $this->getTargetGalaxyPoint()->shouldReturn("[1:2:3]");
    }

    public function it_has_resources_load(): void
    {
        $this->getResourcesLoad()->shouldReturn([
            "e0c85daa-2b52-466d-9c7d-34063c0646d9" => 500,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_key(): void
    {
        $fleetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            350 => 500,
        ];

        $this->beConstructedWith(
            $fleetId,
            $targetGalaxyPoint,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $fleetId, $targetGalaxyPoint, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_value(): void
    {
        $fleetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => "1d9a563e-ed8a-4c5a-a918-903873f2adff",
        ];

        $this->beConstructedWith(
            $fleetId,
            $targetGalaxyPoint,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $fleetId, $targetGalaxyPoint, $resourcesLoad,
        ]);
    }
}
