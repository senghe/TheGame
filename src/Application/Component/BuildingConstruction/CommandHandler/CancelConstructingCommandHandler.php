<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\BuildingContextInterface;
use TheGame\Application\Component\BuildingConstruction\BuildingRepositoryInterface;
use TheGame\Application\Component\BuildingConstruction\Command\CancelConstructingCommand;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenCancelledEvent;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class CancelConstructingCommandHandler
{
    public function __construct(
        private readonly BuildingRepositoryInterface $buildingRepository,
        private readonly BuildingContextInterface $buildingBalanceContext,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CancelConstructingCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $buildingId = new BuildingId($command->getBuildingId());

        $building = $this->buildingRepository->findForPlanetById($planetId, $buildingId);
        if ($building === null) {
            $message = sprintf("The building %s was not found", $command->getBuildingId());
            throw new InconsistentModelException($message);
        }

        $building->cancelUpgrading();

        $cancelledLevel = $building->getCurrentLevel() + 1;
        $resourceRequirements = $this->buildingBalanceContext->getResourceRequirements($cancelledLevel, $building->getType());

        $event = new BuildingConstructionHasBeenCancelledEvent(
            $command->getPlanetId(),
            $building->getType()->value,
            $command->getBuildingId(),
            $cancelledLevel,
            $resourceRequirements->toScalarArray(),
        );
        $this->eventBus->dispatch($event);
    }
}
