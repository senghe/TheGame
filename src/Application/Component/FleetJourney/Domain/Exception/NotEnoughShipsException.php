<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Exception;

use DomainException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;

final class NotEnoughShipsException extends DomainException
{
    public function __construct(PlanetIdInterface|FleetIdInterface|string $argument)
    {
        $message = '';
        if ($argument instanceof PlanetIdInterface) {
            $message = sprintf(
                'Not enough ships stationing on planet %s',
                $argument->getUuid(),
            );
        } else if ($argument instanceof FleetIdInterface) {
            $message = sprintf(
                'Not enough ships on fleet %s',
                $argument->getUuid(),
            );
        } else {
            $message = sprintf(
                'Not enough ships of type %s in fleet',
                $argument,
            );
        }

        parent::__construct($message);
    }
}
