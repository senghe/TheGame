<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use DateTimeImmutable;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartConstructingNewBuildingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasBeenAlreadyBuiltException;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\BuildingConstruction\Domain\Factory\BuildingFactoryInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartConstructingNewBuildingCommandHandler
{
    public function __construct(
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly BuildingContextInterface $buildingBalanceContext,
        private readonly BuildingFactoryInterface $buildingFactory,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartConstructingNewBuildingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingType = BuildingType::from($command->getBuildingType());

        $resourceContextId = $command->getResourceContextId();
        if ($resourceContextId !== null) {
            $resourceContextId = new ResourceId($resourceContextId);
        }
        $building = $this->buildingRepository->findForPlanetByType($planetId, $buildingType, $resourceContextId);
        if ($building !== null) {
            throw new BuildingHasBeenAlreadyBuiltException($planetId, $buildingType);
        }

        $building = $this->buildingFactory->createNew(
            $planetId,
            $buildingType,
            $resourceContextId,
        );

        $resourceRequirements = $this->buildingBalanceContext->getResourceRequirements(
            $building->getCurrentLevel(),
            $building->getType(),
        );
        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $resourceRequirements,
        );

        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $building->getId());
        }

        $buildingDuration = $this->buildingBalanceContext->getBuildingDuration(
            $building->getCurrentLevel(),
            $buildingType
        );
        $buildingFinishDate = new DateTimeImmutable(sprintf("now + %d seconds", $buildingDuration));
        $building->startUpgrading($buildingFinishDate);

        $newLevel = 1;
        $event = new BuildingConstructionHasBeenStartedEvent(
            $command->getPlanetId(),
            $command->getBuildingType(),
            $building->getId()->getUuid(),
            $newLevel,
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
