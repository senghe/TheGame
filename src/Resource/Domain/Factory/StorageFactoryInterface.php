<?php

declare(strict_types=1);

namespace App\Resource\Domain\Factory;

use App\Resource\Domain\Entity\ResourceInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use App\Resource\Domain\Entity\StorageInterface;

interface StorageFactoryInterface
{
    public function create(
        SnapshotInterface $snapshot,
        ResourceInterface $resource
    ): StorageInterface;
}
