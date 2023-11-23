<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class FleetHasCancelledJourneyEvent implements EventInterface
{
    /** @var array<string, int> $resourcesLoad */
    public function __construct(
        private readonly string $fleetId,
        private readonly string $targetGalaxyPoint,
        private readonly array $resourcesLoad,
    ) {
    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getTargetGalaxyPoint(): string
    {
        return $this->targetGalaxyPoint;
    }

    public function getResourcesLoad(): array
    {
        return $this->resourcesLoad;
    }
}
