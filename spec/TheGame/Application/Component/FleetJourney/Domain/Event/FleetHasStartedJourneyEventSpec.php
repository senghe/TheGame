<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class FleetHasStartedJourneyEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 350,
        ];
        $resourcesLoad = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => 500,
        ];

        $this->beConstructedWith(
            $planetId,
            $fleetId,
            $fromGalaxyPoint,
            $toGalaxyPoint,
            $fuelRequirements,
            $resourcesLoad
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("c64b2d71-b70c-428e-9395-ce7ffbf74945");
    }

    public function it_has_fleet_id(): void
    {
        $this->getFleetId()->shouldReturn("dc14be47-7c6a-4530-9327-1a6b3467ae23");
    }

    public function it_from_galaxy_point(): void
    {
        $this->getFromGalaxyPoint()->shouldReturn("[1:2:3]");
    }

    public function it_target_galaxy_point(): void
    {
        $this->getTargetGalaxyPoint()->shouldReturn("[2:3:4]");
    }

    public function it_has_fuel_requirements(): void
    {
        $this->getFuelRequirements()->shouldReturn([
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 350,
        ]);
    }

    public function it_throws_exception_when_initializing_with_fuel_requirements_containing_invalid_array_key(): void
    {
        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            500 => 350,
        ];
        $resourcesLoad = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => 500,
        ];

        $this->beConstructedWith(
            $planetId,
            $fleetId,
            $fromGalaxyPoint,
            $toGalaxyPoint,
            $fuelRequirements,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $fleetId, $fromGalaxyPoint, $toGalaxyPoint, $fuelRequirements, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_fuel_requirements_containing_invalid_array_value(): void
    {
        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => "e6582b8e-0c64-44c7-bffb-76717b9a4473",
        ];
        $resourcesLoad = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => 500,
        ];

        $this->beConstructedWith(
            $planetId,
            $fleetId,
            $fromGalaxyPoint,
            $toGalaxyPoint,
            $fuelRequirements,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $fleetId, $fromGalaxyPoint, $toGalaxyPoint, $fuelRequirements, $resourcesLoad,
        ]);
    }

    public function it_has_resources_load(): void
    {
        $this->getResourcesLoad()->shouldReturn([
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => 500,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_key(): void
    {
        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 350,
        ];
        $resourcesLoad = [
            500 => 350,
        ];

        $this->beConstructedWith(
            $planetId,
            $fleetId,
            $fromGalaxyPoint,
            $toGalaxyPoint,
            $fuelRequirements,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $fleetId, $fromGalaxyPoint, $toGalaxyPoint, $fuelRequirements, $resourcesLoad,
        ]);
    }

    public function it_throws_exception_when_initializing_with_resources_load_containing_invalid_array_value(): void
    {
        $planetId = "c64b2d71-b70c-428e-9395-ce7ffbf74945";
        $fleetId = "dc14be47-7c6a-4530-9327-1a6b3467ae23";
        $fromGalaxyPoint = "[1:2:3]";
        $toGalaxyPoint = "[2:3:4]";
        $fuelRequirements = [
            "00fb6403-ad85-4361-b4fe-785ba3075172" => 350,
        ];
        $resourcesLoad = [
            "2f374b4e-6502-4fd2-addd-42c162d2b826" => "e6582b8e-0c64-44c7-bffb-76717b9a4473",
        ];

        $this->beConstructedWith(
            $planetId,
            $fleetId,
            $fromGalaxyPoint,
            $toGalaxyPoint,
            $fuelRequirements,
            $resourcesLoad
        );

        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $planetId, $fleetId, $fromGalaxyPoint, $toGalaxyPoint, $fuelRequirements, $resourcesLoad,
        ]);
    }
}
