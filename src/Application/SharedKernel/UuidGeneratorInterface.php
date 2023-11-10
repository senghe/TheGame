<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\ResourceMines\Domain\MineIdInterface;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\Component\Shipyard\Domain\ShipyardIdInterface;

interface UuidGeneratorInterface
{
    public function generateNewMineId(): MineIdInterface;

    public function generateNewStorageId(): StorageIdInterface;

    public function generateNewBuildingId(): BuildingIdInterface;

    public function generateNewShipyardId(): ShipyardIdInterface;
}
