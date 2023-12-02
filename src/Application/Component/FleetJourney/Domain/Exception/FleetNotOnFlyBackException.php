<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;

final class FleetNotOnFlyBackException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId)
    {
        $message = sprintf(
            'Fleet %s is not on flyback',
            $fleetId->getUuid(),
        );

        parent::__construct($message);
    }
}
