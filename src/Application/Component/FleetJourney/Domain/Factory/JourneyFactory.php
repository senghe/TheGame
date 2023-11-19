<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Factory;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

final class JourneyFactory implements JourneyFactoryInterface
{
    public function createJourney(
        MissionType $missionType,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        int $journeyDuration,
    ): Journey {
        return new Journey(
            $missionType,
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $journeyDuration,
        );
    }
}
