<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain;

class PlanetStats
{
    public function __construct(
        private readonly PlanetBiome $biome,
        private readonly int $size,
        private readonly int $minTemperature,
        private readonly int $maxTemperature,
    ) {
    }

    public function getBiome(): PlanetBiome
    {
        return $this->biome;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getMinTemperature(): int
    {
        return $this->minTemperature;
    }

    public function getMaxTemperature(): int
    {
        return $this->maxTemperature;
    }
}
