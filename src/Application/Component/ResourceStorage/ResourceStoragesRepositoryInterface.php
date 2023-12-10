<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage;

use TheGame\Application\Component\ResourceStorage\Domain\Entity\StoragesCollection;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;

interface ResourceStoragesRepositoryInterface
{
    public function findForPlanet(PlanetIdInterface $planetId): ?StoragesCollection;
}
