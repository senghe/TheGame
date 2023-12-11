<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain;

interface PlanetStatsRandomizerInterface
{
    public function randomize(int $planetPosition, int $maxPosition): PlanetStats;
}
