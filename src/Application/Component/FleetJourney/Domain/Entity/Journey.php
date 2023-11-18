<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use TheGame\Application\Component\FleetJourney\Domain\GalaxyPointInterface;

class Journey
{
    public function __construct(
        private readonly string $missionType,
        private GalaxyPointInterface $startPoint,
        private GalaxyPointInterface $targetPoint,
    ) {
    }

    public function cancel(): void
    {
        $startPoint = $this->startPoint;
        $this->startPoint = $this->targetPoint;
        $this->targetPoint = $startPoint;
    }
}
