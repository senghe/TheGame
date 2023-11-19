<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

final class CannotTakeJourneyToOutOfBoundGalaxyPointException extends DomainException
{
    public function __construct(GalaxyPointInterface $galaxyPoint)
    {
        $message = sprintf(
            'Not enough fuel resources on planet %s',
            $galaxyPoint->format(),
        );

        parent::__construct($message);
    }
}
