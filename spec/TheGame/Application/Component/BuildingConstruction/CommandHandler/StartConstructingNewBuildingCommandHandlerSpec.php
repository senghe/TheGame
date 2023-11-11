<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\CommandHandler;

use DateTimeInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartConstructingNewBuildingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasBeenAlreadyBuiltException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\BuildingConstruction\Domain\Factory\BuildingFactoryInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartConstructingNewBuildingCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        BuildingRepositoryInterface $buildingRepository,
        BuildingContextInterface $buildingBalanceContext,
        BuildingFactoryInterface $buildingFactory,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $resourceAvailabilityChecker,
            $buildingRepository,
            $buildingBalanceContext,
            $buildingFactory,
            $eventBus,
        );
    }

    public function it_creates_building_when_building_for_the_specified_type_is_not_found(
        BuildingRepositoryInterface $buildingRepository,
        BuildingFactoryInterface $buildingFactory,
        Building $building,
        BuildingContextInterface $buildingBalanceContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourceRequirementsInterface $resourceRequirements,
        EventBusInterface $eventBus,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $resourceContextId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetByType(
            new PlanetId($planetId),
            BuildingType::ResourceStorage,
            new ResourceId($resourceContextId),
        )->willReturn(null);

        $buildingFactory->createNew(
            new PlanetId($planetId),
            BuildingType::ResourceStorage,
            new ResourceId($resourceContextId),
        )->willReturn($building);

        $buildingId = "65225093-9f0d-4b8d-9852-22a5bf0e702b";
        $building->getId()->willReturn(new BuildingId($buildingId));
        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $buildingBalanceContext->getResourceRequirements(1, BuildingType::ResourceStorage)
            ->willReturn($resourceRequirements);

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourceRequirements)
            ->willReturn(true);

        $buildingBalanceContext->getBuildingDuration(1, BuildingType::ResourceStorage)
            ->willReturn(500);
        $building->startUpgrading(Argument::type(DateTimeInterface::class))->shouldBeCalledOnce();

        $resourceRequirements->toScalarArray()->willReturn([
            "0E0987D9-6D13-45A0-9CB5-3000DA9E2174" => 420,
        ]);

        $eventBus->dispatch(Argument::type(BuildingConstructionHasBeenStartedEvent::class))
            ->shouldBeCalledOnce();

        $command = new StartConstructingNewBuildingCommand(
            $planetId,
            BuildingType::ResourceStorage->value,
            $resourceContextId,
        );
        $this->__invoke($command);
    }

    public function it_throws_exception_when_storage_hasnt_enough_resources(
        BuildingRepositoryInterface $buildingRepository,
        BuildingFactoryInterface $buildingFactory,
        Building $building,
        BuildingContextInterface $buildingBalanceContext,
        ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        ResourceRequirementsInterface $resourceRequirements,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $resourceContextId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetByType(
            new PlanetId($planetId),
            BuildingType::ResourceStorage,
            new ResourceId($resourceContextId),
        )->willReturn(null);

        $buildingFactory->createNew(
            new PlanetId($planetId),
            BuildingType::ResourceStorage,
            new ResourceId($resourceContextId),
        )->willReturn($building);

        $buildingId = "65225093-9f0d-4b8d-9852-22a5bf0e702b";
        $building->getId()->willReturn(new BuildingId($buildingId));
        $building->getCurrentLevel()->willReturn(1);
        $building->getType()->willReturn(BuildingType::ResourceStorage);

        $buildingBalanceContext->getResourceRequirements(1, BuildingType::ResourceStorage)
            ->willReturn($resourceRequirements);

        $resourceAvailabilityChecker->check(new PlanetId($planetId), $resourceRequirements)
            ->willReturn(false);

        $command = new StartConstructingNewBuildingCommand(
            $planetId,
            BuildingType::ResourceStorage->value,
            $resourceContextId,
        );
        $this->shouldThrow(InsufficientResourcesException::class)
            ->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_building_is_already_found(
        BuildingRepositoryInterface $buildingRepository,
        Building $building,
    ): void {
        $planetId = "E7AF94C7-488C-46E4-8C44-DCD8F62B2A45";
        $resourceContextId = "52B4E60C-5CCE-4483-968E-D23D9240A18A";

        $buildingRepository->findForPlanetByType(
            new PlanetId($planetId),
            BuildingType::ResourceStorage,
            new ResourceId($resourceContextId),
        )->willReturn($building);

        $command = new StartConstructingNewBuildingCommand(
            $planetId,
            BuildingType::ResourceStorage->value,
            $resourceContextId,
        );
        $this->shouldThrow(BuildingHasBeenAlreadyBuiltException::class)->during('__invoke', [$command]);
    }
}
