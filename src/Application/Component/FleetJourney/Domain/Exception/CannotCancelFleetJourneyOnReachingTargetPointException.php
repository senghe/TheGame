<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;

final class CannotCancelFleetJourneyOnReachingTargetPointException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId)
    {
        $message = sprintf(
            'Cannot cancel fleet % journey (it\'s reaching target point)',
            $fleetId->getUuid(),
        );

        parent::__construct($message);
    }
}
