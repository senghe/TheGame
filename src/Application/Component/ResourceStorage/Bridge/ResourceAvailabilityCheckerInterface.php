<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Bridge;

use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

interface ResourceAvailabilityCheckerInterface
{
    public function check(
        PlanetIdInterface $planetId,
        ResourcesInterface $requirements,
    ): bool;
}
