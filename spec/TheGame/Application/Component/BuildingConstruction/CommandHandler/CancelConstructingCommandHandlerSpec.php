<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\CancelConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasNotBeenBuiltYetFoundException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

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

    public function it_throws_exception_when_cant_find_aggregate(
        BuildingRepositoryInterface $buildingRepository,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";
        $buildingRepository->findForPlanetByType(new PlanetId($planetId), BuildingType::ResourceMine)
            ->willReturn(null);

        $command = new CancelConstructingCommand(
            $planetId,
            BuildingType::ResourceMine->value,
        );
        $this->shouldThrow(BuildingHasNotBeenBuiltYetFoundException::class)
            ->during('__invoke', [$command]);
    }

    public function it_cancels_constructing(
        BuildingRepositoryInterface $buildingRepository,
        BuildingContextInterface $buildingBalanceContext,
        EventBusInterface $eventBus,
        Building $building,
        ResourceRequirementsInterface $resourceRequirements,
    ): void {
        $planetId = "1D632422-951F-4181-A48D-5AD654260B2B";
        $buildingRepository->findForPlanetByType(new PlanetId($planetId), BuildingType::ResourceMine)
            ->willReturn($building);

        $buildingBalanceContext->getResourceRequirements(5, BuildingType::ResourceMine)
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
            BuildingType::ResourceMine->value,
        );
        $this->__invoke($command);
    }
}
