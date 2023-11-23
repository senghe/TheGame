<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

final class JourneyMissionIsNotEligibleException extends DomainException
{
    public function __construct(MissionType $missionType, GalaxyPointInterface $targetPoint)
    {
        $message = sprintf(
            'Cannot perform %s mission to %s',
            $missionType->value,
            $targetPoint->format(),
        );

        parent::__construct($message);
    }
}
