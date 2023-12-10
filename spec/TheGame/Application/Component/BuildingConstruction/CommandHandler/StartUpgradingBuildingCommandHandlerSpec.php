<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\CommandHandler;

use DateTimeInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartUpgradingBuildingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class StartUpgradingBuildingCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        BuildingRepositoryInterface $buildingRepository,
        BuildingContextInterface $buildingBalanceContext,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $resourceAvailabilityChecker,
            $buildingRepository,
            $buildingBalanceContext,
            $eventBus,
        );
    }

    public function it_throws_exception_when_building_is_not_found(
        BuildingRepositoryInterface $buildingRepository,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $buildingId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn(null);

        $command = new StartUpgradingBuildingCommand(
            $planetId,
            $buildingId,
        );
        $this->shouldThrow(InconsistentModelException::class)->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_storage_hasnt_enough_resources(
        BuildingRepositoryInterface $buildingRepository,
        Building $building,
        BuildingContextInterface $buildingBalanceContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface $resourceRequirements,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $buildingId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn($building);

        $building->getId()->willReturn($buildingId);
        $building->getCurrentLevel()->willReturn(50);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $buildingBalanceContext->getResourceRequirements(51, BuildingType::ResourceStorage)
            ->willReturn($resourceRequirements);

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourceRequirements)
            ->willReturn(false);

        $command = new StartUpgradingBuildingCommand(
            $planetId,
            $buildingId,
        );
        $this->shouldThrow(InsufficientResourcesException::class)
            ->during('__invoke', [$command]);
    }

    public function it_starts_upgrading_the_building(
        BuildingRepositoryInterface $buildingRepository,
        Building $building,
        BuildingContextInterface $buildingBalanceContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourcesInterface $resourceRequirements,
        EventBusInterface $eventBus,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $buildingId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetById(new PlanetId($planetId), new BuildingId($buildingId))
            ->willReturn($building);

        $building->getId()->willReturn(new BuildingId($buildingId));
        $building->getCurrentLevel()->willReturn(50);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $buildingBalanceContext->getResourceRequirements(51, BuildingType::ResourceStorage)
            ->willReturn($resourceRequirements);

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourceRequirements)
            ->willReturn(true);

        $buildingBalanceContext->getBuildingDuration(51, BuildingType::ResourceStorage)
            ->willReturn(500);

        $building->startUpgrading(Argument::type(DateTimeInterface::class))->shouldBeCalledOnce();

        $resourceRequirements->toScalarArray()->willReturn([
            "0E0987D9-6D13-45A0-9CB5-3000DA9E2174" => 420,
        ]);

        $eventBus->dispatch(Argument::type(BuildingConstructionHasBeenStartedEvent::class))
            ->shouldBeCalledOnce();

        $command = new StartUpgradingBuildingCommand(
            $planetId,
            $buildingId,
        );
        $this->__invoke($command);
    }
}
