<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use DateTimeImmutable;
use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\BuildingConstruction\Domain\Factory\BuildingFactory;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartConstructingCommandHandler
{
    public function __construct(
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly BuildingContextInterface $buildingBalanceContext,
        private readonly BuildingFactory $buildingFactory,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingType = BuildingType::from($command->getBuildingType());

        $building = $this->buildingRepository->findForPlanet($planetId, $buildingType);
        if ($building === null) {
            $building = $this->buildingFactory->createNew(
                $planetId,
                $buildingType,
            );
        }

        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $this->buildingBalanceContext->getResourceRequirements(
                $building->getCurrentLevel(),
                $building->getType(),
            ),
        );

        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $buildingType);
        }

        $buildingDuration = $this->buildingBalanceContext->getBuildingDuration(
            $building->getCurrentLevel(),
            $buildingType
        );
        $buildingFinishDate = new DateTimeImmutable(sprintf("now + %d seconds", $buildingDuration));
        $building->startUpgrading($buildingFinishDate);

        $resourceRequirements = $this->buildingBalanceContext->getResourceRequirements(
            $building->getCurrentLevel(),
            $building->getType()
        );
        $event = new BuildingConstructionHasBeenStartedEvent(
            $command->getPlanetId(),
            $command->getBuildingType(),
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
