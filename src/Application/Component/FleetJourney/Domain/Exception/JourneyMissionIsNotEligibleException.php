<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;

final class JourneyMissionIsNotEligibleException extends DomainException
{
    public function __construct(FleetMissionType $missionType, GalaxyPointInterface $targetPoint)
    {
        $message = sprintf(
            'Cannot perform %s mission to %s',
            $missionType->value,
            $targetPoint->format(),
        );

        parent::__construct($message);
    }
}
