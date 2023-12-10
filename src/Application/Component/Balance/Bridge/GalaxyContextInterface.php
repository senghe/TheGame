<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Balance\Bridge;

interface GalaxyContextInterface
{
    public function getMaxGalaxyNumber(): int;

    public function getMaxSolarSystem(): int;

    public function getMaxPlanetPosition(): int;
}
