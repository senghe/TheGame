<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Factory;

use TheGame\Application\Component\Galaxy\Domain\Entity\SolarSystem;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class SolarSystemFactory implements SolarSystemFactoryInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {

    }

    public function create(
        int $galaxyNumber,
        int $solarSystemNumber,
    ): SolarSystem {
        return new SolarSystem(
            $this->uuidGenerator->generateNewSolarSystemId(),
            $galaxyNumber,
            $solarSystemNumber,
        );
    }
}
