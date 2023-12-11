<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Factory;

use TheGame\Application\Component\Galaxy\Domain\Entity\Planet;
use TheGame\Application\Component\Galaxy\Domain\PlanetStatsRandomizer;
use TheGame\Application\Component\Galaxy\Domain\PlanetStatsRandomizerInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class PlanetFactory implements PlanetFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly PlanetStatsRandomizerInterface $planetStatsRandomizer,
    ) {

    }

    public function create(
        PlayerIdInterface $playerId,
        GalaxyPointInterface $galaxyPoint,
        int $maxPlanetPosition,
    ): Planet {
        $planetPosition = $galaxyPoint->getPlanet();

        return new Planet(
            $this->uuidGenerator->generateNewPlanetId(),
            $playerId,
            $this->planetStatsRandomizer->randomize($planetPosition, $maxPlanetPosition),
            $planetPosition,
        );
    }
}
