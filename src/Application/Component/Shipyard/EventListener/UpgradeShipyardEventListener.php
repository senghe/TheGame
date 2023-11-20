<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\EventListener;

use TheGame\Application\Component\Balance\Bridge\ShipyardContextInterface;
use TheGame\Application\Component\BuildingConstruction\Domain\BuildingId;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ShipyardConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\Shipyard\Domain\Factory\ShipyardFactoryInterface;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;

final class UpgradeShipyardEventListener
{
    public function __construct(
        private readonly ShipyardRepositoryInterface $shipyardRepository,
        private readonly ShipyardFactoryInterface $shipyardFactory,
        private readonly ShipyardContextInterface $shipyardBalanceContext,
    ) {
    }

    public function __invoke(ShipyardConstructionHasBeenFinishedEvent $event): void
    {
        $planetId = new PlanetId($event->getPlanetId());
        $buildingId = new BuildingId($event->getBuildingId());

        $shipyard = $event->getUpgradedLevel() === 1
            ? $this->shipyardFactory->create($planetId, $buildingId)
            : $this->shipyardRepository->findForBuilding($planetId, $buildingId);

        if ($shipyard === null) {
            throw new ShipyardHasNotBeenFoundException($buildingId);
        }

        $shipyard->upgrade(
            $this->shipyardBalanceContext->getShipyardProductionLimit($event->getUpgradedLevel()),
        );
    }
}
