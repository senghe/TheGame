<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Factory;

use TheGame\Application\Component\Galaxy\Domain\Entity\Planet;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

interface PlanetFactoryInterface
{
    public function create(
        PlayerIdInterface $playerId,
        GalaxyPointInterface $galaxyPoint,
        int $maxPlanetPosition,
    ): Planet;
}
