<?php

declare(strict_types=1);

namespace App\Component\Resource\Port;

use App\SharedKernel\Domain\Entity\PlanetInterface;

interface PlanetRepositoryInterface
{
    public function findOneById(int $planetId): PlanetInterface;
}