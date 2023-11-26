<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Command;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class StartJourneyCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $mission = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 25,
        ];
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => 500,
        ];

        $this->beConstructedWith(
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("e5835dba-aef6-4b15-9d7c-cfb177959f6d");
    }

    public function it_has_target_galaxy_point(): void
    {
        $this->getTargetGalaxyPoint()->shouldReturn("[1:2:3]");
    }

    public function it_has_mission_type(): void
    {
        $this->getMissionType()->shouldReturn("transport");
    }

    public function it_has_ships_taking_journey(): void
    {
        $this->getShipsTakingJourney()->shouldReturn([
            "light-fighter" => 25,
        ]);
    }

    public function it_throws_exception_when_initializing_with_ships_taking_journey_containing_invalid_array_key(): void
    {
        $planetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $mission = "transport";
        $shipsTakingJourney = [
            500 => 25,
        ];
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => 500,
        ];

        $this->beConstructedWith(
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_ships_taking_journey_containing_invalid_array_value(): void
    {
        $planetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $mission = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 25,
        ];
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => "8bd4aaac-2d64-4b3e-be30-56be1096d9e9",
        ];

        $this->beConstructedWith(
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad,
        ]);
    }

    public function it_has_resources_load(): void
    {
        $this->getResourcesLoad()->shouldReturn([
            "5966f38b-2add-43e7-884b-64cf6569666f" => 500,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_key(): void
    {
        $planetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $mission = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 25,
        ];
        $resourcesLoad = [
            350 => 500,
        ];

        $this->beConstructedWith(
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_value(): void
    {
        $planetId = "e5835dba-aef6-4b15-9d7c-cfb177959f6d";
        $targetGalaxyPoint = "[1:2:3]";
        $mission = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 25,
        ];
        $resourcesLoad = [
            "5966f38b-2add-43e7-884b-64cf6569666f" => "1d9a563e-ed8a-4c5a-a918-903873f2adff",
        ];

        $this->beConstructedWith(
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $targetGalaxyPoint, $mission, $shipsTakingJourney, $resourcesLoad,
        ]);
    }
}
