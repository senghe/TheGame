<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory;

use InvalidArgumentException;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ShipyardConstructionHasBeenFinishedEvent;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;

final class BuildingTypeEventFactorySpec extends ObjectBehavior
{
    public function it_creates_a_construction_finished_event_for_mine_building(
        Building $building
    ): void {
        $planetId = "A16D8FB1-FD3B-4C25-A1FC-D04857EF917A";
        $building->getPlanetId()->willReturn(new PlanetId($planetId));

        $buildingId = "4110CF0F-81FD-4C63-83FF-19069C597BDB";
        $building->getId()->willReturn(new BuildingId($buildingId));

        $resourceContextId = "3FA9C84B-F2A7-44D0-8EA3-63DD66D23899";
        $building->getResourceContextId()->willReturn(new ResourceId($resourceContextId));

        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceMine);

        $this->createConstructingFinishedEvent($building)
            ->shouldReturnAnInstanceOf(ResourceMineConstructionHasBeenFinishedEvent::class);
    }

    public function it_throws_exception_creating_event_for_mine_building_without_resource_context_id(
        Building $building
    ): void {
        $planetId = "A16D8FB1-FD3B-4C25-A1FC-D04857EF917A";
        $building->getPlanetId()->willReturn(new PlanetId($planetId));

        $buildingId = "4110CF0F-81FD-4C63-83FF-19069C597BDB";
        $building->getId()->willReturn(new BuildingId($buildingId));

        $building->getResourceContextId()->willReturn(null);

        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceMine);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('createConstructingFinishedEvent', [$building]);
    }

    public function it_creates_a_construction_finished_event_for_storage_building(
        Building $building
    ): void {
        $planetId = "A16D8FB1-FD3B-4C25-A1FC-D04857EF917A";
        $building->getPlanetId()->willReturn(new PlanetId($planetId));

        $buildingId = "4110CF0F-81FD-4C63-83FF-19069C597BDB";
        $building->getId()->willReturn(new BuildingId($buildingId));

        $resourceContextId = "3FA9C84B-F2A7-44D0-8EA3-63DD66D23899";
        $building->getResourceContextId()->willReturn(new ResourceId($resourceContextId));

        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $this->createConstructingFinishedEvent($building)
            ->shouldReturnAnInstanceOf(ResourceStorageConstructionHasBeenFinishedEvent::class);
    }

    public function it_throws_exception_creating_event_for_storage_building_without_resource_context_id(
        Building $building
    ): void {
        $planetId = "A16D8FB1-FD3B-4C25-A1FC-D04857EF917A";
        $building->getPlanetId()->willReturn(new PlanetId($planetId));

        $buildingId = "4110CF0F-81FD-4C63-83FF-19069C597BDB";
        $building->getId()->willReturn(new BuildingId($buildingId));

        $building->getResourceContextId()->willReturn(null);

        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('createConstructingFinishedEvent', [$building]);
    }

    public function it_creates_a_construction_finished_event_for_shipyard_building(
        Building $building
    ): void {
        $planetId = "A16D8FB1-FD3B-4C25-A1FC-D04857EF917A";
        $building->getPlanetId()->willReturn(new PlanetId($planetId));

        $buildingId = "4110CF0F-81FD-4C63-83FF-19069C597BDB";
        $building->getId()->willReturn(new BuildingId($buildingId));

        $resourceContextId = "3FA9C84B-F2A7-44D0-8EA3-63DD66D23899";
        $building->getResourceContextId()->willReturn(new ResourceId($resourceContextId));

        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::Shipyard);

        $this->createConstructingFinishedEvent($building)
            ->shouldReturnAnInstanceOf(ShipyardConstructionHasBeenFinishedEvent::class);
    }

    public function it_creates_a_generic_construction_finished_event(): void
    {
        throw new SkippingException('Cannot spec this scenario, because of using enum');
    }
}
