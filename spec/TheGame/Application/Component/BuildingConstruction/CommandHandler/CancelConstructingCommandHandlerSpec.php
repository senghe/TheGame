<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\CancelConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class CancelConstructingCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        BuildingRepositoryInterface $buildingRepository,
        BuildingContextInterface $buildingBalanceContext,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $buildingRepository,
            $buildingBalanceContext,
            $eventBus,
        );
    }

    public function it_throws_exception_when_cant_find_building(
        BuildingRepositoryInterface $buildingRepository,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";
        $buildingId = "d6949ca7-157d-4019-9267-c7a61af33b01";
        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn(null);

        $command = new CancelConstructingCommand(
            $planetId,
            $buildingId,
        );
        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [$command]);
    }

    public function it_cancels_constructing(
        BuildingRepositoryInterface $buildingRepository,
        BuildingContextInterface    $buildingBalanceContext,
        EventBusInterface           $eventBus,
        Building                    $building,
        ResourcesInterface          $resourceRequirements,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";
        $buildingId = "d6949ca7-157d-4019-9267-c7a61af33b01";
        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn($building);

        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $buildingBalanceContext->getResourceRequirements(6, BuildingType::ResourceStorage)
            ->willReturn($resourceRequirements);

        $building->cancelUpgrading()->shouldBeCalledOnce();
        $building->getCurrentLevel()->willReturn(5);
        $resourceRequirements->toScalarArray()
            ->willReturn([
                "26E29382-BA42-4675-B643-93E9006B089B" => 500,
            ]);

        $eventBus->dispatch(Argument::type(BuildingConstructionHasBeenCancelledEvent::class))
            ->shouldBeCalledOnce();

        $command = new CancelConstructingCommand(
            $planetId,
            $buildingId,
        );
        $this->__invoke($command);
    }
}
