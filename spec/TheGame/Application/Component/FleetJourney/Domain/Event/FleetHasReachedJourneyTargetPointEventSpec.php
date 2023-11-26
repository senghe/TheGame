<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class FleetHasReachedJourneyTargetPointEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $mission = "transport";
        $fleetId = "99315453-a2a6-4fa1-8f77-c99f7cfa0e10";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            "879dcdb6-4383-4b5c-89f9-6239cbfae8b0" => 500,
        ];

        $this->beConstructedWith(
            $mission, $fleetId, $targetGalaxyPoint, $resourcesLoad,
        );
    }

    public function it_has_mission(): void
    {
        $this->getMission()->shouldReturn("transport");
    }

    public function it_fleet_id(): void
    {
        $this->getFleetId()->shouldReturn("99315453-a2a6-4fa1-8f77-c99f7cfa0e10");
    }

    public function it_has_target_galaxy_point(): void
    {
        $this->getTargetGalaxyPoint()->shouldReturn("[1:2:3]");
    }

    public function it_has_resources_load(): void
    {
        $this->getResourcesLoad()->shouldReturn([
            "879dcdb6-4383-4b5c-89f9-6239cbfae8b0" => 500,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_key(): void
    {
        $mission = 'transport';
        $fleetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            350 => 500,
        ];

        $this->beConstructedWith(
            $mission, $fleetId, $targetGalaxyPoint, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $mission, $fleetId, $targetGalaxyPoint, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_value(): void
    {
        $mission = 'transport';
        $fleetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => "1d9a563e-ed8a-4c5a-a918-903873f2adff",
        ];

        $this->beConstructedWith(
            $mission, $fleetId, $targetGalaxyPoint, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $mission, $fleetId, $targetGalaxyPoint, $resourcesLoad,
        ]);
    }
}
