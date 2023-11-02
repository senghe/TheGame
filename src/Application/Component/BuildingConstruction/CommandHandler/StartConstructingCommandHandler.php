<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\StartConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenStartedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\BuildingConstruction\Domain\Factory\BuildingFactory;
use TheGame\Application\Component\ResourceStorage\Bridge\ResourceAvailabilityCheckerInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartConstructingCommandHandler
{
    public function __construct(
        private readonly ResourceAvailabilityCheckerInterface $resourceAvailabilityChecker,
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly BuildingFactory $buildingFactory,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingType = new BuildingType($command->getBuildingType());

        $building = $this->buildingRepository->findForPlanet($planetId, $buildingType);
        if ($building === null) {
            $building = $this->buildingFactory->createNew(
                $planetId,
                $buildingType,
            );
        }

        $hasEnoughResources = $this->resourceAvailabilityChecker->check(
            $planetId,
            $building->getCosts(),
        );

        if ($hasEnoughResources === false) {
            throw new InsufficientResourcesException($planetId, $buildingType);
        }

        $building->startUpgrading();

        $event = new BuildingConstructionHasBeenStartedEvent(
            $command->getPlanetId(),
            $command->getBuildingType(),
        );
        $this->eventBus->dispatch($event);
    }
}
