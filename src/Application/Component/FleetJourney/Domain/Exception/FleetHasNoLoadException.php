<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;

final class FleetHasNoLoadException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId)
    {
        $message = sprintf(
            'Fleet %s has no load',
            $fleetId->getUuid(),
        );

        parent::__construct($message);
    }
}
