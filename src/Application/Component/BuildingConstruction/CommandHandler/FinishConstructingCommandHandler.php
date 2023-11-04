<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\FinishConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory\BuildingTypeEventFactory;
use TheGame\Application\Component\BuildingConstruction\Domain\Exception\BuildingHasNotBeenBuiltYetFoundException;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class FinishConstructingCommandHandler
{
    public function __construct(
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly EventBusInterface $eventBus,
        private readonly BuildingTypeEventFactory $buildingTypeEventFactory,
    ) {
    }

    public function __invoke(FinishConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingType = BuildingType::fromName($command->getBuildingType());

        $building = $this->buildingRepository->findForPlanet($planetId, $buildingType);
        if ($building === null) {
            throw new BuildingHasNotBeenBuiltYetFoundException(
                $planetId,
                $buildingType
            );
        }

        $building->finishUpgrading();

        $event = $this->buildingTypeEventFactory->createConstructingFinishedEvent($building);
        $this->eventBus->dispatch($event);
    }
}
