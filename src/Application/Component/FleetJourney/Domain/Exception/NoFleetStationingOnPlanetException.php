<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;

final class NoFleetStationingOnPlanetException extends DomainException
{
    public function __construct(PlanetIdInterface $planetId)
    {
        $message = sprintf(
            'No fleet is stationing on planet %s',
            $planetId->getUuid(),
        );

        parent::__construct($message);
    }
}
