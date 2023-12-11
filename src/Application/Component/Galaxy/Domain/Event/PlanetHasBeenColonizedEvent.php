<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Event;

use TheGame\Application\SharedKernel\EventInterface;

final class PlanetHasBeenColonizedEvent implements EventInterface
{
    public function __construct(
        private readonly string $playerId,
        private readonly string $coordinates,
    ) {

    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getCoordinates(): string
    {
        return $this->coordinates;
    }
}
