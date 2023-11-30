<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\ShipsGroup;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Domain\Resources;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class FleetFactorySpec extends ObjectBehavior
{
    public function let(UuidGeneratorInterface $uuidGenerator): void
    {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_fleet(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $fleetId = new FleetId("393acaa4-0fdf-44ab-afa9-352b3ac8e826");
        $uuidGenerator->generateNewFleetId()->willReturn($fleetId);

        $shipsTakingJourney = [new ShipsGroup(
            'light-fighter', 10, 50, 200,
        ), new ShipsGroup(
            'warship', 100, 20, 5000,
        )];
        $stationingGalaxyPoint = new GalaxyPoint(1, 2, 3);
        $resourcesLoad = new Resources();
        $resourcesLoad->addResource(
            new ResourceAmount(new ResourceId("8ddf9a4b-380d-4051-a1d7-9370ca4d7b3e"), 100),
        );

        $createdFleet = $this->create(
            $shipsTakingJourney, $stationingGalaxyPoint, $resourcesLoad,
        );
        $createdFleet->shouldBeAnInstanceOf(Fleet::class);
        $createdFleet->getId()->shouldReturn($fleetId);
        $createdFleet->getStationingGalaxyPoint()->shouldReturn($stationingGalaxyPoint);
        $createdFleet->getResourcesLoad()->shouldReturn($resourcesLoad->toScalarArray());
    }
}
