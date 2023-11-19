<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain;

interface GalaxyPointInterface
{
    public function getGalaxy(): int;

    public function getSolarSystem(): int;

    public function getPlanet(): int;

    /** @return int[] */
    public function toArray(): array;
}
