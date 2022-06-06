<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\Resource\Domain\Entity\StorageInterface;
use App\Component\Resource\Domain\Service\ResourceMetadata\ResourceMetadataInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;

interface StorageFactoryInterface
{
    public function create(
        PlanetInterface $planet,
        SnapshotInterface $snapshot,
        ResourceMetadataInterface $resourceMetadata
    ): StorageInterface;
}
