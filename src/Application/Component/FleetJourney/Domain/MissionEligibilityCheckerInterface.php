<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

interface MissionEligibilityCheckerInterface
{
    public function isEligible(
        FleetMissionType $missionType,
        Fleet            $fleet,
    ): bool;
}
