<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use DateTimeImmutable;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartConstructingNewBuildingCommand;
use TheGame\Application\Component\BuildingConstruction\Command\StartUpgradingBuildingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\BuildingConstruction\Domain\Factory\BuildingFactoryInterface;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class StartUpgradingBuildingCommandHandler
{
    public function __construct(
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly BuildingContextInterface $buildingBalanceContext,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartUpgradingBuildingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingId = new BuildingId($command->getBuildingId());

        $building = $this->buildingRepository->findForPlanetById($planetId, $buildingId);
        if ($building === null) {
            $message = sprintf("The building %s was not found", $command->getBuildingId());
            throw new InconsistentModelException($message);
        }

        $resourceRequirements = $this->buildingBalanceContext->getResourceRequirements(
            $building->getCurrentLevel()+1,
            $building->getType(),
        );
        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $resourceRequirements,
        );

        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $buildingId);
        }

        $buildingDuration = $this->buildingBalanceContext->getBuildingDuration(
            $building->getCurrentLevel(),
            $building->getType(),
        );
        $buildingFinishDate = new DateTimeImmutable(sprintf("now + %d seconds", $buildingDuration));
        $building->startUpgrading($buildingFinishDate);

        $newLevel = $building->getCurrentLevel()+1;
        $event = new BuildingConstructionHasBeenStartedEvent(
            $command->getPlanetId(),
            $building->getType()->value,
            $building->getId()->getUuid(),
            $newLevel,
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
