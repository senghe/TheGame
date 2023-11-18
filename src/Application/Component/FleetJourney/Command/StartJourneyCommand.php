<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class StartJourneyCommand implements CommandInterface
{
    public function __construct(
        private readonly string $fleetId,
        private readonly int $targetGalaxy,
        private readonly int $targetSolarSystem,
        private readonly int $targetPlanet,
        private readonly string $missionType,
    ) {

    }

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getTargetGalaxy(): int
    {
        return $this->targetGalaxy;
    }

    public function getTargetSolarSystem(): int
    {
        return $this->targetSolarSystem;
    }

    public function getTargetPlanet(): int
    {
        return $this->targetPlanet;
    }

    public function getMissionType(): string
    {
        return $this->missionType;
    }
}
