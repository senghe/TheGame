<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;

final class CannotMergeShipGroupsOfDifferentShips extends DomainException
{
    public function __construct(string $shipName1, string $shipName2)
    {
        $message = sprintf(
            'Cannot merge ship group of ships %s with ship group of ships %s',
            $shipName1,
            $shipName2,
        );

        parent::__construct($message);
    }
}
