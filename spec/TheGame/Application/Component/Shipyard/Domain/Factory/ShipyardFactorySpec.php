<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class ShipyardFactorySpec extends ObjectBehavior
{
    public function let(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_shipyard(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $shipyardId = new ShipyardId("B8670928-FFCA-4D03-AE0F-2BFB605578EB");

        $uuidGenerator->generateNewShipyardId()->willReturn($shipyardId);

        $planetId = new PlanetId("55833879-5897-44CA-9BF4-6835479FBFC0");
        $buildingId = new BuildingId("79AB0E22-74EB-4C16-A9FB-0CD56A7A42ED");

        $createdShipyard = $this->create($planetId, $buildingId);
        $createdShipyard->getId()->shouldReturn($shipyardId);
        $createdShipyard->getPlanetId()->shouldReturn($planetId);
        $createdShipyard->getBuildingId()->shouldReturn($buildingId);
    }
}
