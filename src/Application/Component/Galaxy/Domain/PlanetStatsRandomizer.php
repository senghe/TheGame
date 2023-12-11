<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain;

final class PlanetStatsRandomizer implements PlanetStatsRandomizerInterface
{
    public function randomize(int $planetPosition, int $maxPosition): PlanetStats
    {
        $biomes = PlanetBiome::cases();
        $biome = $biomes[rand(0, count($biomes)-1)];
        $fieldsNumber = $this->randomizeFieldsNumber($planetPosition, $maxPosition);
        [$minTemperature, $maxTemperature] = $this->randomizeTemperature($planetPosition, $maxPosition);

        return new PlanetStats(
            $biome, $fieldsNumber, $minTemperature, $maxTemperature
        );
    }

    private function randomizeFieldsNumber(int $planetPosition, int $maxPosition): int
    {
        $pivot = $planetPosition / $maxPosition;
        $statsMetadata = [
            [0.15, 45, 95],
            [0.3, 45, 120],
            [0.4, 120, 165],
            [0.6, 120, 185],
            [0.8, 150, 185],
            [0.9, 185, 265],
            [1.0, 235, 265],
        ];

        $minFieldsNumber = $maxFieldsNumber = 0;
        foreach ($statsMetadata as $metadata) {
            $pivotBound = $metadata[0];
            $minFieldsNumberBound = $metadata[1];
            $maxFieldsNumberBound = $metadata[2];

            if ($pivot < $pivotBound) {
                $minFieldsNumber = $minFieldsNumberBound;
                $maxFieldsNumber = $maxFieldsNumberBound;
                break;
            }
        }

        return rand($minFieldsNumber, $maxFieldsNumber);
    }

    /** @return int[] */
    private function randomizeTemperature(int $planetPosition, int $maxPosition): array
    {
        $minPivot = ($planetPosition / $maxPosition) * 100;
        $maxPivot = 100;

        $lowerBounds = [-45, 145];
        $higherBounds = [-5, 275];

        $randPivot = rand($minPivot, $maxPivot) / 100;
        $lowerTemperature = $lowerBounds[0] + $randPivot * $lowerBounds[1]-$lowerBounds[0];

        $randPivot = rand($minPivot, $maxPivot) / 100;
        $higherTemperature = $higherBounds[0] + $randPivot * $higherBounds[1]-$higherBounds[0];

        if ($lowerTemperature > $higherTemperature) {
            return [$higherTemperature, $lowerTemperature];
        }

        return [$lowerTemperature, $higherTemperature];
    }
}
