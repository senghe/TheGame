<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines;

use TheGame\Application\Component\ResourceMines\Domain\Entity\MinesCollection;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;

interface ResourceMinesRepositoryInterface
{
    public function findForPlanet(PlanetId $planetId): ?MinesCollection;
}
