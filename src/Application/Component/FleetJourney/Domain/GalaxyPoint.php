<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

final class GalaxyPoint implements GalaxyPointInterface
{
    public function __construct(
        private readonly int $galaxy,
        private readonly int $solarSystem,
        private readonly int $planet,
    ) {

    }

    public function getGalaxy(): int
    {
        return $this->galaxy;
    }

    public function getSolarSystem(): int
    {
        return $this->solarSystem;
    }

    public function getPlanet(): int
    {
        return $this->planet;
    }
}
