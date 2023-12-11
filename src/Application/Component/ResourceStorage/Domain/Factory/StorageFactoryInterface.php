<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Factory;

use TheGame\Application\Component\ResourceStorage\Domain\Entity\Storage;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;

interface StorageFactoryInterface
{
    public function createNew(ResourceIdInterface $resourceId): Storage;
}
