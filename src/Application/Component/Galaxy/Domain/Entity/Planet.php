<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Entity;

use TheGame\Application\Component\Galaxy\Domain\PlanetStats;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;

class Planet
{
    public function __construct(
        protected readonly PlanetIdInterface $planetId,
        protected readonly PlayerIdInterface $playerId,
        protected readonly PlanetStats $planetStats,
        protected readonly int $position,
    ) {

    }

    public function getId(): PlanetIdInterface
    {
        return $this->planetId;
    }

    public function getPlayerId(): PlayerIdInterface
    {
        return $this->playerId;
    }

    public function isOnPosition(int $position): bool
    {
        return $this->position === $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getStats(): PlanetStats
    {
        return $this->planetStats;
    }
}
