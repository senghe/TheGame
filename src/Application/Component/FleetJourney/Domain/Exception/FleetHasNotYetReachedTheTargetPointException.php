<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;

final class FleetHasNotYetReachedTheTargetPointException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId, int $timeLeft)
    {
        $message = sprintf(
            'Fleet %s has not yet reached the target (%d seconds left)',
            $fleetId->getUuid(),
            $timeLeft,
        );

        parent::__construct($message);
    }
}
