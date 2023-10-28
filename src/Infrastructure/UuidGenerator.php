<?php

declare(strict_types=1);

namespace TheGame\Infrastructure;

use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generateNewStorageId(): StorageIdInterface
    {

    }
}
