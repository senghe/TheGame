<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

final class MissionEligibilityChecker implements MissionEligibilityCheckerInterface
{
    public function isEligible(
        FleetMissionType $missionType,
        Fleet $fleet,
    ): bool {
        $containsColonizationShip = $fleet->containsShipsOfClass(ShipClass::Colonization);
        if ($missionType === FleetMissionType::Colonization && $containsColonizationShip === false) {
            return false;
        }

        return true;
    }
}
