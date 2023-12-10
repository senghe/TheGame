<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Domain\Factory;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class BuildingFactorySpec extends ObjectBehavior
{
    public function let(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_new_building(
        UuidGeneratorInterface $uuidGenerator,
        BuildingIdInterface $buildingId,
        PlanetIdInterface $planetId,
        ResourceIdInterface $resourceContextId,
    ): void {
        $uuidGenerator->generateNewBuildingId()
            ->willReturn($buildingId);

        $buildingType = BuildingType::ResourceMine;

        $building = $this->createNew($planetId, $buildingType, $resourceContextId);

        $building->getPlanetId()->shouldReturn($planetId);
        $building->getType()->shouldReturn($buildingType);
        $building->getCurrentLevel()->shouldReturn(0);
    }
}
