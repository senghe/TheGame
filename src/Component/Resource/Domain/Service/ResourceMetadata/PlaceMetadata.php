<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Service\ResourceMetadata;

use App\Component\SharedKernel\Domain\PlanetInterface;

final class PlaceMetadata implements ResourceMetadataInterface
{
    public function getCode(): string
    {
        return 'place';
    }

    public function isForInitialPlanet(): bool
    {
        return true;
    }

    public function getStartSpeed(PlanetInterface $planet): int
    {
        return 0;
    }

    public function getStartAmount(PlanetInterface $planet): int
    {
        return 0;
    }

    public function getMaxAmount(PlanetInterface $planet): int
    {

    }

    public function getRandomStartAmount(PlanetInterface $planet): int
    {

    }

    public function getRandomMiningPotential(PlanetInterface $planet): int
    {

    }

    public function canBeTraded(): bool
    {
        return false;
    }

    public function chanceToBeMet(): int
    {
        return 100;
    }

    public function takenOnProcessEnd(): bool
    {
        return true;
    }
}