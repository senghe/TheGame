<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners;

use TheGame\Application\Component\ResourceMiners\Domain\Entity\MinesCollection;
use TheGame\Application\SharedKernel\Domain\PlanetId;

interface ResourceMinersRepositoryInterface
{
    public function findForPlanet(PlanetId $planetId): ?MinesCollection;
}
