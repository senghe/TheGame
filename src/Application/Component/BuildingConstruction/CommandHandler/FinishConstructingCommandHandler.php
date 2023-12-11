<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\FinishConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory\BuildingTypeEventFactoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class FinishConstructingCommandHandler
{
    public function __construct(
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly EventBusInterface $eventBus,
        private readonly BuildingTypeEventFactoryInterface $buildingTypeEventFactory,
    ) {
    }

    public function __invoke(FinishConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingId = new BuildingId($command->getBuildingId());

        $building = $this->buildingRepository->findForPlanetById($planetId, $buildingId);
        if ($building === null) {
            $message = sprintf("The building %s was not found", $command->getBuildingId());
            throw new InconsistentModelException($message);
        }

        $building->finishUpgrading();

        $event = $this->buildingTypeEventFactory->createConstructingFinishedEvent($building);
        $this->eventBus->dispatch($event);
    }
}
