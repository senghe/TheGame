<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\FinishConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory\BuildingTypeEventFactoryInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasNotBeenBuiltYetFoundException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class FinishConstructingCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        BuildingRepositoryInterface $buildingRepository,
        EventBusInterface $eventBus,
        BuildingTypeEventFactoryInterface $buildingTypeEventFactory,
    ): void {
        $this->beConstructedWith(
            $buildingRepository,
            $eventBus,
            $buildingTypeEventFactory,
        );
    }

    public function it_throws_exception_when_aggregate_is_not_found(
        BuildingRepositoryInterface $buildingRepository,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";
        $buildingId = "ea01f1c2-2f1d-4c0f-832a-b09d442612ae";

        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn(null);

        $command = new FinishConstructingCommand(
            $planetId,
            $buildingId,
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [$command]);
    }

    public function it_finishes_constructing(
        BuildingRepositoryInterface $buildingRepository,
        EventBusInterface $eventBus,
        BuildingTypeEventFactoryInterface $buildingTypeEventFactory,
        Building $building,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";

        $buildingRepository->findForPlanetByType(new PlanetId($planetId), BuildingType::ResourceStorage)
            ->willReturn($building);

        $building->finishUpgrading()->shouldBeCalledOnce();

        $resourceId = "73140C59-DE9C-4959-8E18-1271EB32D76A";
        $event = new ResourceStorageConstructionHasBeenFinishedEvent(
            $planetId,
            $resourceId,
            1,
        );
        $buildingTypeEventFactory->createConstructingFinishedEvent($building)
            ->willReturn($event);

        $eventBus->dispatch(Argument::type(ResourceStorageConstructionHasBeenFinishedEvent::class))
            ->shouldBeCalledOnce();

        $command = new FinishConstructingCommand(
            $planetId,
            BuildingType::ResourceStorage->value,
        );
        $this->__invoke($command);
    }
}
