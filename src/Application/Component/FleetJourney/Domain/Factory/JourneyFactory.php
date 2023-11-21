<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Factory;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class JourneyFactory implements JourneyFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {

    }

    public function createJourney(
        FleetIdInterface $fleetId,
        MissionType $missionType,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        int $journeyDuration,
    ): Journey {
        $id = $this->uuidGenerator->generateNewJourneyId();

        return new Journey(
            $id,
            $fleetId,
            $missionType,
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $journeyDuration,
        );
    }
}
