<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ShipyardConstructionHasBeenFinishedEvent;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\EventInterface;

final class BuildingTypeEventFactory
{
    public function createConstructingFinishedEvent(Building $building): EventInterface
    {
        $type = $building->getType();
        switch ($type) {
            case BuildingType::ResourceMine: {
                return new ResourceMineConstructionHasBeenFinishedEvent(
                    $building->getPlanetId()->getUuid(),
                    $building->getId()->getUuid(),
                    $building->getResourceContextId()->getUuid(),
                    $building->getCurrentLevel(),
                );
            }
            case BuildingType::ResourceStorage: {
                return new ResourceStorageConstructionHasBeenFinishedEvent(
                    $building->getPlanetId()->getUuid(),
                    $building->getId()->getUuid(),
                    $building->getResourceContextId()->getUuid(),
                    $building->getCurrentLevel(),
                );
            }
            case BuildingType::Shipyard: {
                return new ShipyardConstructionHasBeenFinishedEvent(
                    $building->getPlanetId()->getUuid(),
                    $building->getId()->getUuid(),
                    $building->getCurrentLevel(),
                );
            }
        }

        return new BuildingConstructionHasBeenFinishedEvent(
            $building->getPlanetId()->getUuid(),
            $building->getId()->getUuid(),
            $type->value,
            $building->getCurrentLevel(),
        );
    }
}
