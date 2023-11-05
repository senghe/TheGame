<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain\Factory;

use TheGame\Application\Component\ResourceMines\Domain\Entity\Mine;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

interface MineFactoryInterface
{
    public function createNew(ResourceIdInterface $resourceId): Mine;
}
