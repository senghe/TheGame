<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Service\ResourceMetadata;

use App\Component\SharedKernel\Domain\PlanetInterface;

final class GravitonMetadata implements ResourceMetadataInterface
{
    public function getCode(): string
    {

    }

    public function isForInitialPlanet(): bool
    {

    }

    public function getStartSpeed(PlanetInterface $planet): int
    {

    }

    public function getStartAmount(PlanetInterface $planet): int
    {
        
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
        return true;
    }

    public function chanceToBeMet(): int
    {

    }

    public function takenOnProcessEnd(): bool
    {
        return false;
    }
}