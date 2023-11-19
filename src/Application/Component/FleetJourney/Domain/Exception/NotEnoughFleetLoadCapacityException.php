<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class NotEnoughFleetLoadCapacityException extends DomainException
{
    public function __construct(FleetIdInterface $fleetId, int $capacityAvailable, int $capacityNeeded)
    {
        $message = sprintf(
            'Not enough load on fleet %s (%d needed, %d available)',
            $fleetId->getUuid(),
            $capacityNeeded,
            $capacityAvailable
        );

        parent::__construct($message);
    }
}
