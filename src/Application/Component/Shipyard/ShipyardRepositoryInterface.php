<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard;

use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;

interface ShipyardRepositoryInterface
{
    public function findAggregate(ShipyardIdInterface $shipyardId): ?Shipyard;
}
