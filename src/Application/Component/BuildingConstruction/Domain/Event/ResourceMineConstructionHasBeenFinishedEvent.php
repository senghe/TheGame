<?php

declare(strict_types=1);

namespace TheGame\Application\Component\BuildingConstruction\Domain\Event;

use TheGame\Application\SharedKernel\Domain\BuildingType;
use TheGame\Application\SharedKernel\EventInterface;

final class ResourceMineConstructionHasBeenFinishedEvent extends BuildingConstructionHasBeenFinishedEvent implements EventInterface
{
    public function __construct(
        private readonly string $planetId,
        private readonly string $buildingId,
        private readonly string $resourceContextId,
        private readonly int $upgradedLevel,
    ) {
        parent::__construct(
            $this->planetId,
            BuildingType::ResourceMine->value,
            $this->buildingId,
            $this->upgradedLevel,
        );
    }

    public function getResourceContextId(): string
    {
        return $this->resourceContextId;
    }
}
