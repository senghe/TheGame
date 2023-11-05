<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event\Factory;

use TheGame\Application\Component\BuildingConstruction\Domain\Entity\Building;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\BuildingConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceMineConstructionHasBeenFinishedEvent;
use TheGame\Application\Component\BuildingConstruction\Domain\Event\ResourceStorageConstructionHasBeenFinishedEvent;
use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

final class BuildingTypeEventFactory implements BuildingTypeEventFactoryInterface
{
    public function createConstructingFinishedEvent(Building $building): BuildingConstructionHasBeenFinishedEvent
    {
        $type = $building->getType();
        switch ($type) {
            case BuildingType::ResourceMine: {
                /** @var ResourceIdInterface $resourceContextId */
                $resourceContextId = $building->getResourceContextId();
                return new ResourceMineConstructionHasBeenFinishedEvent(
                    $building->getPlanetId()->getUuid(),
                    $resourceContextId->getUuid(),
                    $building->getCurrentLevel(),
                );
            }
            case BuildingType::ResourceStorage: {
                /** @var ResourceIdInterface $resourceContextId */
                $resourceContextId = $building->getResourceContextId();
                return new ResourceStorageConstructionHasBeenFinishedEvent(
                    $building->getPlanetId()->getUuid(),
                    $resourceContextId->getUuid(),
                    $building->getCurrentLevel(),
                );
            }
        }

        return new BuildingConstructionHasBeenFinishedEvent(
            $building->getPlanetId()->getUuid(),
            $type->value,
            $building->getCurrentLevel(),
        );
    }
}
