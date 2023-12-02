<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Factory;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

interface JourneyFactoryInterface
{
    public function createJourney(
        FleetIdInterface $fleetId,
        MissionType $missionType,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        int $journeyDuration,
    ): Journey;
}
