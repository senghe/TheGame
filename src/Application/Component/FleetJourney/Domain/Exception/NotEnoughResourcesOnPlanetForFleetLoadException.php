<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class NotEnoughResourcesOnPlanetForFleetLoadException extends DomainException
{
    public function __construct(PlanetIdInterface $planetId)
    {
        $message = sprintf(
            'Not enough fuel resources on planet %s',
            $planetId->getUuid(),
        );

        parent::__construct($message);
    }
}
