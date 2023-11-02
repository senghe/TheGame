<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\CancelConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingType;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasNotBeenBuiltYetFoundException;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class CancelConstructingCommandHandler
{
    public function __construct(
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CancelConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingType = new BuildingType($command->getBuildingType());

        $building = $this->buildingRepository->findForPlanet($planetId, $buildingType);
        if ($building === null) {
            throw new BuildingHasNotBeenBuiltYetFoundException(
                $planetId,
                $buildingType
            );
        }

        $building->cancelUpgrading();

        $event = new BuildingConstructionHasBeenCancelledEvent(
            $command->getPlanetId(),
            $command->getBuildingType(),
        );
        $this->eventBus->dispatch($event);
    }
}
