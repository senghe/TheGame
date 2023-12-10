<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Factory;

use TheGame\Application\Component\Galaxy\Domain\Entity\SolarSystem;

interface SolarSystemFactoryInterface
{
    public function create(
        int $galaxyNumber,
        int $solarSystemNumber,
    ): SolarSystem;
}
