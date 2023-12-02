<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;

final class CannotMergeShipGroupsOfDifferentTypeException extends DomainException
{
    public function __construct(string $shipType1, string $shipType2)
    {
        $message = sprintf(
            'Cannot merge ship group of type %s with ship group of type %s',
            $shipType1,
            $shipType2,
        );

        parent::__construct($message);
    }
}
