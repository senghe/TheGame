<?php

declare(strict_types=1);

namespace App\Domain\Resource\Factory;

use App\Domain\Resource\Entity\ResourceInterface;
use App\Domain\Resource\Entity\SnapshotInterface;
use App\Domain\Resource\Entity\StorageInterface;

interface StorageFactoryInterface
{
    public function create(
        SnapshotInterface $snapshot,
        ResourceInterface $resource
    ): StorageInterface;
}
