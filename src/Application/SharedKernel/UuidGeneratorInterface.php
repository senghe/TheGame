<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel;

use TheGame\Application\Component\BuildingConstruction\Domain\BuildingIdInterface;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;

interface UuidGeneratorInterface
{
    public function generateNewStorageId(): StorageIdInterface;

    public function generateNewBuildingId(): BuildingIdInterface;
}
