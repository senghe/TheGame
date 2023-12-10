<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Exception;

use DomainException;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

final class PlanetAlreadyColonizedException extends DomainException
{
    public function __construct(
        GalaxyPointInterface $galaxyPoint,
        PlayerIdInterface $playerId,
    ) {
        $message = sprintf(
            "Player %s can't colonize already colonized planet %s",
            $playerId->getUuid(),
            $galaxyPoint->format(),
        );

        parent::__construct($message);
    }
}
