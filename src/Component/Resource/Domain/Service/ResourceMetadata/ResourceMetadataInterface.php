<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Service\ResourceMetadata;

use App\SharedKernel\Domain\Entity\PlanetInterface;

interface ResourceMetadataInterface
{
    public function getCode(): string;

    public function isForInitialPlanet(): bool;

    public function getStartAmount(PlanetInterface $planet): int;

    public function getStartSpeed(PlanetInterface $planet): int;

    public function getMaxAmount(PlanetInterface $planet): int;

    public function getRandomStartAmount(PlanetInterface $planet): int;

    public function getRandomMiningPotential(PlanetInterface $planet): int;

    public function canBeTraded(): bool;

    public function chanceToBeMet(): int;

    public function takenOnProcessEnd(): bool;
}