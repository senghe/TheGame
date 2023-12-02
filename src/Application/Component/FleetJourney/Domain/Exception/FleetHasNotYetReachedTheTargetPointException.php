<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;

final class FleetHasNotYetReachedTheTargetPointException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId, ?int $timeLeft = null)
    {
        $message = sprintf(
            'Fleet %s has not yet reached the target point',
            $fleetId->getUuid(),
        );

        if ($timeLeft !== null) {
            $message .= sprintf(' (%d seconds left)', $timeLeft);
        }

        parent::__construct($message);
    }
}
